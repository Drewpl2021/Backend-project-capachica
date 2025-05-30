FROM jenkins/jenkins:lts

USER root

# Instalar dependencias base
RUN apt update && apt install -y \
    apt-transport-https lsb-release ca-certificates wget gnupg \
    curl unzip git zip software-properties-common

# Repositorio de PHP 8.2 (requerido para Laravel 10)
RUN wget -qO - https://packages.sury.org/php/apt.gpg | gpg --dearmor -o /etc/apt/trusted.gpg.d/php.gpg && \
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list

# Instalar PHP 8.2 y extensiones necesarias
RUN apt update && apt install -y \
    php8.2 php8.2-cli php8.2-mbstring php8.2-xml php8.2-curl \
    php8.2-bcmath php8.2-mysql php8.2-zip php8.2-sqlite3

# Instalar Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar Node.js (opcional, para frontend)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt install -y nodejs

# SonarScanner (opcional)
RUN curl -o /tmp/sonar.zip -L https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/sonar-scanner-cli-5.0.1.3006-linux.zip && \
    unzip /tmp/sonar.zip -d /opt && \
    ln -s /opt/sonar-scanner-*/bin/sonar-scanner /usr/local/bin/sonar-scanner && \
    rm /tmp/sonar.zip

# Permisos para Jenkins
RUN chown -R jenkins:jenkins /var/jenkins_home

USER jenkins
