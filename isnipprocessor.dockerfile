FROM php:8.1-cli

LABEL maintainer="csipb abrari"

# Install required libraries and Python
RUN apt-get update && apt-get install -y \
    python3 \
    wget unzip libpng-dev libjpeg-dev libncurses5 libstdc++6 zlib1g \
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

# Set working directory
WORKDIR /isnip/html

# Set default entrypoint
ENTRYPOINT ["php", "index.php"]
