pipeline {
    agent any
    environment {
        PROD_SERVER = '3.215.249.125'  // Replace with your actual production server IP
        SSH_CREDENTIALS = 'c5f1eb9d-fce7-4cbb-a86f-7da927315011'  // Ensure this matches the credentials ID in Jenkins
    }
    stages {
        stage('Checkout Code') {
            steps {
                git branch: 'main', url: 'https://github.com/IamRuyy/SWE40006-Group2.git'  // Replace with your actual GitHub URL
            }
        }

        stage('Run Tests') {
            steps {
                script {
                    // Run the tests and capture the exit code
                    def testResult = sh(script: '''
                        set -e
                        echo "Starting tests..."
                        cd $WORKSPACE
                        php tests/run-tests.php  // Updated path to run-tests.php
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
}
