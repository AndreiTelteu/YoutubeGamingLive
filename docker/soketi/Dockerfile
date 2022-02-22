FROM quay.io/soketi/soketi:0.17-16-alpine
WORKDIR "/app"

RUN npm install npm pm2 -g

ENTRYPOINT ["pm2-runtime", "/app/ecosystem.config.js"]

# tried some weired options
# ENTRYPOINT ["pm2-runtime", "/app/ecosystem.config.js", "--raw", "--error", "/proc/self/fd/2", "--output", "/proc/self/fd/2"]
# ENTRYPOINT ["pm2-runtime", "/app/ecosystem.config.js", "--error", "/proc/self/fd/2", "--output", "/proc/self/fd/2"]
# ENTRYPOINT ["/usr/local/bin/node", "/app/bin/pm2.js", "--config=/app/config.json"]
# ENTRYPOINT ["/usr/local/bin/node", "/usr/local/lib/node_modules/pm2/lib/binaries/CLI.js", "--no-daemon", "start"]
# CMD ["/app/dist/cli/index.js", "--", "start", "--config=/app/config.json"]
