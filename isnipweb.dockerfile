FROM php:8.1-apache

LABEL maintainer="csipb abrari"

# Install dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libzip-dev \
    unzip \
    wget \
    libncurses5 \
    libstdc++6 \
    zlib1g \
    python3 \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && a2enmod rewrite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# ✅ Install Bowtie2 (copy only files, skip folders)
RUN wget https://github.com/BenLangmead/bowtie2/releases/download/v2.5.4/bowtie2-2.5.4-linux-x86_64.zip -O /tmp/bowtie2.zip && \
    unzip /tmp/bowtie2.zip -d /opt/bowtie2 && \
    find /opt/bowtie2/bowtie2-2.5.4-linux-x86_64 -maxdepth 1 -type f -exec cp {} /usr/local/bin/ \; && \
    chmod +x /usr/local/bin/bowtie2* && \
    rm /tmp/bowtie2.zip

# ✅ Add the FASTA file and build index
COPY example.fasta /opt/bowtie-index/
RUN bowtie2-build /opt/bowtie-index/example.fasta /opt/bowtie-index/index

# Fix Apache ServerName warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copy project files to web root
COPY ./CapstoneProject-10-main/ /var/www/html/
RUN chown -R www-data:www-data /var/www/html/
