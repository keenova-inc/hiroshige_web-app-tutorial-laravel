From node:24-bullseye

RUN apt update && apt install -y \
#ssコマンド使用のため
iproute2

ENV NODE_ENV=development

WORKDIR /work
COPY ./front/ .

CMD ["npm", "run", "dev"]
# CMD ["tail", "-f", "/dev/null"]