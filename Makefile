.PHONY: build start restart stop

build:
	BUILD = true ./start.sh
start:
	./start.sh

restart:
	./restart.sh

stop:
	./stop.sh