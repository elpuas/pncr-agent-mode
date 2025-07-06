# Makefile for PBCR Agent Mode Plugin Development
# Provides convenient shortcuts for common development tasks

# Default target
.DEFAULT_GOAL := help

# Colors for output
GREEN = \033[0;32m
YELLOW = \033[0;33m
NC = \033[0m # No Color

## Install development dependencies
install:
	@echo "$(GREEN)Installing Composer dependencies...$(NC)"
	composer install
	@echo "$(GREEN)Development environment ready!$(NC)"

## Run PHP CodeSniffer (lint check)
lint:
	@echo "$(GREEN)Running PHP CodeSniffer...$(NC)"
	composer run lint

## Fix PHP coding standards issues automatically
lint-fix:
	@echo "$(GREEN)Fixing PHP coding standards...$(NC)"
	composer run lint-fix

## Run PHPUnit tests
test:
	@echo "$(GREEN)Running PHPUnit tests...$(NC)"
	composer run test

## Run tests with coverage report
test-coverage:
	@echo "$(GREEN)Running tests with coverage...$(NC)"
	composer run test-coverage
	@echo "$(YELLOW)Coverage report generated in tests/coverage/$(NC)"

## Clean generated files
clean:
	@echo "$(GREEN)Cleaning generated files...$(NC)"
	rm -rf tests/coverage/
	rm -f tests/results.xml
	rm -f tests/clover.xml
	rm -f tests/coverage.txt

## Update Composer dependencies
update:
	@echo "$(GREEN)Updating Composer dependencies...$(NC)"
	composer update

## Validate composer.json file
validate:
	@echo "$(GREEN)Validating composer.json...$(NC)"
	composer validate

## Run all quality checks (lint + test)
check: lint test
	@echo "$(GREEN)All quality checks completed!$(NC)"

## Show this help message
help:
	@echo "$(GREEN)PBCR Agent Mode Plugin - Development Commands$(NC)"
	@echo ""
	@echo "$(YELLOW)Available commands:$(NC)"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  $(GREEN)%-15s$(NC) %s\n", $$1, $$2}'
	@echo ""
	@echo "$(YELLOW)Usage:$(NC)"
	@echo "  make [command]"
	@echo ""

.PHONY: install lint lint-fix test test-coverage clean update validate check help
