pipeline {
    agent any
    environment {
        PROD_SERVER = '3.215.249.125'  // Replace with your actual production server IP
        SSH_CREDENTIALS = 'c5f1eb9d-fce7-4cbb-a86f-7da927315011'
    }
    stages {
        stage('Checkout Code') {
            steps {
                git branch: 'main', url: 'https://github.com/IamRuyy/SWE40006-Group2.git'  // Replace with your actual GitHub URL
            }
        }

        // stage('Run Tests') {
        //     steps {
        //         // Run tests directly on Jenkins, as it serves as the test server
        //         sh '''
        //         # Ensure we're in the right directory (Jenkins workspace)
        //         cd $WORKSPACE
        //         # Run the test script (adjust path if needed)
        //         php /var/www/html/run-tests.php
        //         '''
        //     }
        // }

        stage('Deploy to Production Server') {
            when {
                expression {
                    currentBuild.result == null || currentBuild.result == 'SUCCESS'
                }
            }
            steps {
                sshagent(credentials: [SSH_CREDENTIALS]) {
                    sh """
                    ssh -o StrictHostKeyChecking=no ec2-user@${PROD_SERVER} 'rm -rf /var/www/html/*'  // Replace ec2-user if necessary
                    scp -o StrictHostKeyChecking=no -r * ec2-user@${PROD_SERVER}:/var/www/html/
                    """
                }
            }
        }
    }
}
