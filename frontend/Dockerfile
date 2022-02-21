FROM node:16-bullseye
WORKDIR "/app"

RUN npm install pm2 -g

ENTRYPOINT ["/docker-entrypoint.sh"]
CMD ["su", "node", "-c", "pm2-runtime /app/frontend/ecosystem.config.js"]
