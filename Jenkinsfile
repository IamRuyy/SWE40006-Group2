pipeline {
    agent any
    stages {
        stage('Checkout') {
            steps {
                git branch: 'main', url: 'https://github.com/IamRuyy/SWE40006-Group2.git'
            }
        }
        stage('Build') {
            steps {
                sh 'echo Building the application...'
            }
        }
        stage('Test') {
            steps {
                sh 'echo Running tests...'
            }
        }
        stage('Deploy to EC2') {
    steps {
        sshagent(credentials: ['ec2-ssh-key']) {
            sh 'scp -o StrictHostKeyChecking=no artifact.zip ec2-user@<EC2_Public_IP>:/home/ec2-user/'
            sh 'ssh ec2-user@<EC2_Public_IP> "unzip /home/ec2-user/artifact.zip -d /var/www/html/"'
        }
    }
}

    }
}
