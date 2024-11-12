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
                // Run tests directly on Jenkins, as it serves as the test server
                sh '''
                cd $WORKSPACE
                php run-tests.php
                '''
            }
            // Fail the build if this stage fails
            post {
                failure {
                    echo 'Tests failed. Aborting deployment.'
                    currentBuild.result = 'FAILURE'  // Mark the build as failed
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
                    # Clear the contents of the production server's web root safely
                    ssh -o StrictHostKeyChecking=no ec2-user@${PROD_SERVER} 'find /var/www/html -mindepth 1 -delete'
                    
                    # Deploy the new files
                    scp -o StrictHostKeyChecking=no -r * ec2-user@${PROD_SERVER}:/var/www/html/
                    """
                }
            }
        }
    }
}
