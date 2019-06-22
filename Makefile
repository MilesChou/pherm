#!/usr/bin/make -f

PHP_MAJOR_VERSION := $(shell php -r "echo PHP_MAJOR_VERSION;")

.PHONY: clean clean-all check test coverage

# ---------------------------------------------------------------------

all: tests

clean:
	rm -rf ./build

clean-all: clean
	rm -rf ./vendor

check:
	php vendor/bin/phpcs

test: check
ifeq ($(PHP_MAJOR_VERSION), 7)
	phpdbg -qrr vendor/bin/phpunit
else
	php vendor/bin/phpunit
endif

static: test
	php vendor/bin/phpstan analyse src --level=7

coverage: test
	@if [ "`uname`" = "Darwin" ]; then open build/coverage/index.html; fi

