From node:24-bullseye

RUN apt update && apt install -y \
#ssコマンド使用のため
iproute2

WORKDIR /work

CMD ["npm", "run", "dev"]