pipeline {
    agent any

    stages {
        stage('Clone Repository') {
            steps {
                git branch: 'main', url: 'https://github.com/IamRuyy/SWE40006-Group2.git'
            }
        }

        stage('Deploy to EC2') {
            steps {
                sshagent (credentials: ['a163e271-94af-4d8d-9d16-ab9a60758db8']) {
                    sh 'scp -o StrictHostKeyChecking=no index.html ec2-user@54.211.163.148:/var/www/html/index.html'
                }
            }
        }
    }
}
