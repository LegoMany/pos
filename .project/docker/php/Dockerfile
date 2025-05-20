FROM in2code/php-dev:8.4-fpm

USER root

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt install symfony-cli

USER app:app

# Add ls -alF shorthand to container
RUN echo "alias ll='ls -alF'" >> ~/.custombashrc

# Add .in2coderc to shell sessions
RUN echo "source ~/.custombashrc" >> ~/.bashrc