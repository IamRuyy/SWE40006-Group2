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
                # Install Composer if not already installed
                if [ ! -f "composer.phar" ]; then
                    curl -sS https://getcomposer.org/installer | php
                fi

                # Install dependencies including PHPUnit
                php composer.phar install
                '''
            }
        }

        stage('Run Tests') {
            steps {
                script {
                    // Run PHPUnit tests and capture the result
                    def testResult = sh(script: '''
                        set -e
                        echo "Starting tests with PHPUnit..."
                        cd $WORKSPACE
                        vendor/bin/phpunit --configuration phpunit.xml
                    ''', returnStatus: true)

                    // Check the test result and mark the build as failed if tests failed
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
                    currentBuild.result == null || currentBuild.result == 'SUCCESS'  // Deploy only if previous stages succeeded
                }
            }
            steps {
                sshagent(credentials: [SSH_CREDENTIALS]) {
                    sh """
                    # Ensure target directory exists on production server
                    ssh -o StrictHostKeyChecking=no ec2-user@${PROD_SERVER} 'mkdir -p /var/www/html/swe40006'

                    # Clear the contents of the production server's target directory safely
                    ssh -o StrictHostKeyChecking=no ec2-user@${PROD_SERVER} 'find /var/www/html/swe40006 -mindepth 1 -delete'
                    
                    # Deploy the new files to the specified directory
                    scp -o StrictHostKeyChecking=no -r * ec2-user@${PROD_SERVER}:/var/www/html/swe40006/
                    """
                }
            }
        }
    }
    post {
        always {
            junit '**/tests/results/*.xml' // Path where PHPUnit results are stored
        }
        failure {
            echo 'Build failed!'
        }
        success {
            echo 'Build completed successfully!'
        }
    }
}
