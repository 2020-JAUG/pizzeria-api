include .env

.PHONY: up down stop prune ps shell logs

default: up

help : docker.mk
	@sed -n 's/^##//p' $<

up:
	@echo "Starting up containers for $(APP_NAME)..."
	docker-compose pull
	docker-compose up -d --remove-orphans


down: stop

stop:
	@echo "Stopping containers for $(APP_NAME)..."
	@docker-compose stop

shell:
	docker exec -ti -e COLUMNS=$(shell tput cols) -e LINES=$(shell tput lines) $(shell docker ps --filter name='$(APP_NAME)_$(filter-out $@,$(MAKECMDGOALS))' --format "{{ .ID }}") sh
