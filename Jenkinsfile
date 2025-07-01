pipeline {
    agent {
        docker {
            image 'php:8.2-cli'
            args '-v /var/run/docker.sock:/var/run/docker.sock'
        }
    }

    environment {
        PROJECT_DIR = 'Backend-project-capachica'
        DB_HOST = 'mysql'
    }

    stages {
        stage('Clonar repositorio') {
            steps {
                git branch: 'developP',
                    credentialsId: 'github-token',
                    url: 'https://github.com/Drewpl2021/Backend-project-capachica.git'
            }
        }

        stage('Instalar dependencias') {
            steps {
                dir("${PROJECT_DIR}") {
                    sh '''
                        apt-get update && apt-get install -y unzip libzip-dev git
                        curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
                        docker-php-ext-install zip pdo_mysql
                        composer install --no-interaction --prefer-dist
                        cp .env.ci .env
                        php artisan key:generate
                    '''
                }
            }
        }

        stage('Migrar base de datos') {
            steps {
                dir("${PROJECT_DIR}") {
                    sh 'php artisan migrate --seed --force'
                }
            }
        }

        stage('Ejecutar pruebas') {
            steps {
                dir("${PROJECT_DIR}") {
                    sh 'php artisan test --testsuite=Feature --log-junit storage/test-results.xml --coverage-clover storage/coverage/clover.xml'
                }
            }
        }

        stage('Análisis SonarQube') {
            steps {
                dir("${PROJECT_DIR}") {
                    sh """
                        docker run --rm \
                          -e SONAR_HOST_URL="http://172.17.0.1:9000"
                          -e SONAR_LOGIN="squ_b9ebb805459783f90dfd89df2e3386dbec425b1c" \
                          -v \$(pwd):/usr/src \
                          -w /usr/src \
                          sonarsource/sonar-scanner-cli \
                          -Dsonar.projectKey=Backend-project-capachica \
                          -Dsonar.sources=. \
                          -Dsonar.php.coverage.reportPaths=storage/coverage/clover.xml \
                          -Dsonar.verbose=true
                    """
                }
            }
        }
    }

    post {
        failure {
            slackSend channel: '#devops',
                     message: "❌ Pipeline Fallido: ${env.JOB_NAME} (${env.BUILD_URL})"
        }
        success {
            slackSend channel: '#devops',
                     message: "✅ Pipeline Exitoso: ${env.JOB_NAME} (${env.BUILD_URL})"
        }
    }
}
