FROM node:18-alpine as builder

WORKDIR /app

COPY package.json pnpm-lock.yaml ./

COPY . .

RUN npm install -g pnpm && pnpm install

RUN pnpm build
