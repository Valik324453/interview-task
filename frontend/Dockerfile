# Используем официальный образ Node.js
FROM node:14-alpine

# Устанавливаем рабочую директорию
WORKDIR /app

# Копируем package.json и package-lock.json
COPY package*.json ./

# Устанавливаем зависимости
RUN npm install

# Копируем остальной код приложения
COPY . .

# Собираем приложение
RUN npm run build

# Команда по умолчанию для контейнера
CMD ["npm", "run", "serve"]