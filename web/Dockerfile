FROM node:13.12.0-alpine

WORKDIR /app

COPY package.json ./
COPY yarn.lock ./

RUN npm install

CMD ["npm", "start"]