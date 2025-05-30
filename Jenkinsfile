pipeline {
    agent any

    environment {
        SONARQUBE_ENV = 'sonarqube' // Nombre exacto configurado en Jenkins
        PROJECT_DIR = 'Backend-project-capachica'
    }

    stages {
        stage('Clonar repositorio') {
            steps {
                git branch: 'main',
                    credentialsId: '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',
                    url: 'https://github.com/Drewpl2021/Backend-project-capachica.git'
            }
        }

        stage('Preparar entorno Laravel') {
            steps {
                dir("${PROJECT_DIR}") {
                    echo '📦 Instalando dependencias'
                    sh 'composer install --no-interaction --prefer-dist'

                    echo '📝 Copiando entorno de CI'
                    sh 'cp .env.ci .env'

                    echo '🔐 Generando APP_KEY si falta'
                    sh 'php artisan key:generate || echo "Key ya existente"'

                    echo '🧹 Limpiando caché'
                    sh 'php artisan config:clear'
                    sh 'php artisan cache:clear'
                }
            }
        }

        stage('Migraciones y seeders') {
            steps {
                dir("${PROJECT_DIR}") {
                    echo '🔄 Ejecutando migraciones y seeders en entorno CI'
                    sh 'yes | php artisan migrate'
                    sh 'php artisan migrate:fresh --seed'
                }
            }
        }

        stage('Ejecutar pruebas con cobertura') {
            steps {
                dir("${PROJECT_DIR}") {
                    echo '🧪 Ejecutando pruebas Feature con cobertura Clover + JUnit'
                    sh 'php artisan test --testsuite=Feature --log-junit storage/test-results.xml --coverage-clover storage/coverage/clover.xml'
                }
            }
        }

        stage('Análisis SonarQube') {
            steps {
                dir("${PROJECT_DIR}") {
                    echo '📊 Ejecutando análisis de calidad de código'
                    withSonarQubeEnv("${SONARQUBE_ENV}") {
                        sh 'sonar-scanner'
                    }
                }
            }
        }

        stage('Quality Gate') {
            steps {
                timeout(time: 4, unit: 'MINUTES') {
                    echo '✅ Esperando resultados del Quality Gate de SonarQube'
                    waitForQualityGate abortPipeline: true
                }
            }
        }

        stage('Fin del pipeline') {
            steps {
                echo '🎉 Pipeline completado con éxito.'
            }
        }
    }
}