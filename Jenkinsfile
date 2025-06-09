pipeline {
    agent {
        docker {
            image 'php:8.2-cli'  // Imagen oficial con PHP 8.2
            args '-v /var/run/docker.sock:/var/run/docker.sock'
        }
    }

    environment {
        SONARQUBE_ENV = 'sonarqube'
        PROJECT_DIR = 'Backend-project-capachica'
        DB_HOST = 'mysql'  // Servicio de DB (necesitarás docker-compose)
    }

    stages {
        stage('Clonar repositorio') {
            steps {
                git branch: 'developP',  // Usando tu rama
                    credentialsId: 'github-token',  // Verifica que este ID existe
                    url: 'https://github.com/Drewpl2021/Backend-project-capachica.git'
            }
        }

        stage('Instalar dependencias') {
            steps {
                dir("${PROJECT_DIR}") {
                    // Instalar Composer y extensiones PHP
                    sh '''
                        apt-get update && apt-get install -y unzip libzip-dev
                        curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
                        docker-php-ext-install zip pdo_mysql
                        composer install --no-interaction --prefer-dist
                        cp .env.ci .env
                        php artisan key:generate
                    '''
                }
            }
        }

        stage('Base de datos') {
            steps {
                dir("${PROJECT_DIR}") {
                    sh 'php artisan migrate --seed --force'
                }
            }
        }

        stage('Pruebas') {
            steps {
                dir("${PROJECT_DIR}") {
                    sh 'php artisan test --testsuite=Feature --log-junit storage/test-results.xml --coverage-clover storage/coverage/clover.xml'
                }
            }
        }

        stage('Análisis SonarQube') {
            steps {
                dir("${PROJECT_DIR}") {
                    script {
                        // Descargar sonar-scanner 5.0+
                        sh '''
                            curl -o /tmp/sonar-scanner.zip -L https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/sonar-scanner-cli-5.0.1.3006-linux.zip
                            unzip /tmp/sonar-scanner.zip -d /opt
                            ln -s /opt/sonar-scanner-*/bin/sonar-scanner /usr/local/bin/sonar-scanner
                        '''
                        withSonarQubeEnv("${SONARQUBE_ENV}") {
                            sh 'sonar-scanner -Dsonar.projectKey=Backend-project-capachica -Dsonar.php.coverage.reportPaths=storage/coverage/clover.xml'
                        }
                    }
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
