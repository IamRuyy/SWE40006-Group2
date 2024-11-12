pipeline {
    agent any
    environment {
        PROD_SERVER = '3.215.249.125'  
        SSH_CREDENTIALS = 'c5f1eb9d-fce7-4cbb-a86f-7da927315011'
    }
    stages {
        stage('Checkout Code') {
            steps {
                git branch: 'main', url: 'https://github.com/IamRuyy/SWE40006-Group2.git'
            }
        }

        stage('Install Dependencies') {
            steps {
                sh '''
                    if [ ! -f composer.phar ]; then
                        php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
                        php composer-setup.php
                        php -r "unlink('composer-setup.php');"
                    fi
                    php composer.phar install
                '''
            }
        }

        stage('Run Tests') {
            steps {
                script {
                    def testResult = sh(script: '''
                        set -e
                        echo "Starting tests with PHPUnit..."
                        cd $WORKSPACE
                        vendor/bin/phpunit --configuration phpunit.xml
                    ''', returnStatus: true)

                    if (testResult != 0) {
                        echo 'Tests failed with exit code ' + testResult.toString()
                        error('Aborting pipeline due to test failures.')
                    } else {
                        echo 'All tests passed.'
                    }
                }
            }
        }

        stage('Deploy to Production Server') {
            when {
                expression {
                    currentBuild.result == null || currentBuild.result == 'SUCCESS'
                }
            }
            steps {
                sshagent(credentials: [SSH_CREDENTIALS]) {
                    sh """
                    ssh -o StrictHostKeyChecking=no ec2-user@${PROD_SERVER} 'mkdir -p /var/www/html/swe40006'
                    ssh -o StrictHostKeyChecking=no ec2-user@${PROD_SERVER} 'find /var/www/html/swe40006 -mindepth 1 -delete'
                    scp -o StrictHostKeyChecking=no -r * ec2-user@${PROD_SERVER}:/var/www/html/swe40006/
                    """
                }
            }
        }
    }
    post {
        always {
            echo 'Build completed.'
            junit 'tests/results/junit.xml' // Publish test results to Jenkins
        }
        failure {
            echo 'Build failed!'
        }
        success {
            echo 'Build succeeded!'
        }
    }
}
