From node:24-bullseye

RUN apt update && apt install -y \
#ssコマンド使用のため
iproute2

WORKDIR /work
COPY ./front/ .
RUN npm install

CMD ["npm", "run", "dev"]
# CMD ["tail", "-f", "/dev/null"]