# Accelerating development without losing control
**Sebastian Bergmann**

> The state of software development in 2026 is at a critical juncture. Although AI-assisted development tools promise unprecedented speed, without proper foundations, this velocity can become recklessness rather than progress. This hands-on workshop will demonstrate how to embrace modern development practices, including AI agents, while maintaining control mechanisms that ensure quality, architectural integrity, and long-term sustainability.
>
> Participants will learn how to leverage automation and modern tools not as a replacement for discipline, but as a means of enabling higher-velocity development that is still grounded in rigorous quality assurance. The workshop focuses on the principle that speed without control leads to technical debt and system fragility, while control without speed fails to meet modern market demands. The way forward is to establish comprehensive automated safety nets that enable teams to work faster because they can trust their quality controls.
>
> Through practical exercises involving PHP and industry-standard tools, participants will implement a comprehensive quality infrastructure that facilitates rapid development. The workshop progresses from traditional quality practices to their evolution in AI-assisted workflows, demonstrating test-driven development patterns in which humans write specifications as tests and AI agents generate implementations.
>
> Participants will leave with skills that they can apply immediately to accelerate their development processes while strengthening, not weakening, quality controls. The workshop shows that the practices that enable safe AI collaboration are the same as those that enable high-velocity human development. This convergence is the true path to sustainable speed, achieving both velocity and quality through automation and discipline.
>
> The example application developed in this repository is used by [Sebastian Bergmann](https://phpunit.expert/?ref=github) in his "[Accelerating development without losing control](https://phpunit.expert/presentations/accelerating-development-without-losing-control.html?ref=github)" workshop.

---

## EventFlow

A lightweight ticketing and seat-reservation system for events.
This repository is the starter project for the "[Accelerating development without losing control](https://phpunit.expert/presentations/accelerating-development-without-losing-control.html?ref=github)" workshop.
The toolchain is already configured for you.
During the workshop you will grow the domain on top of it.

### Requirements

- PHP 8.5.7 or later
- The [Xdebug](https://xdebug.org/) or [PCOV](https://github.com/krakjoe/pcov) extension (needed to collect code coverage)

All other tools (PHPUnit, PHPStan, Deptrac, Infection, PHP-CS-Fixer, Composer) are committed to this repository as PHAR files in `tools/`, so there is nothing else to download.

### Homework before the workshop

Please do this before the workshop starts:

```bash
git clone https://github.com/thePHPcc/EventFlow.git
cd EventFlow
./tools/composer install
./tools/composer ci
```

`composer ci` must finish green.
If it does not, please reach out before the workshop.

### Running the quality gate in a container

If you cannot install PHP 8.5.7 locally, a `Containerfile` is provided so you can run everything inside a container with Podman or Docker.
The image is pinned to the exact PHP version used in the workshop and bundles the Xdebug extension needed for coverage.

Build the image once:

```bash
podman build -t eventflow-ci -f Containerfile .
```

Then run the complete quality gate against your working copy:

```bash
podman run --rm -v "$PWD":/app:Z eventflow-ci
```

The container generates the autoloader and runs `composer ci` for you, so a green run means you are ready for the workshop.
Replace `podman` with `docker` if you prefer; on systems without SELinux you can drop the `:Z` suffix from the volume mount.

### The quality gate

`composer ci` runs every feedback loop in order.
Each one catches a different class of mistake, and each one also runs in the CI pipeline (`.github/workflows/ci.yml`):

| Command                          | Tool         | Catches                                              |
|----------------------------------|--------------|------------------------------------------------------|
| `composer coding-standard`       | PHP CS Fixer | Formatting and coding-style deviations               |
| `composer static-analysis`       | PHPStan      | Type errors and impossible code (level 10)           |
| `composer architecture`          | Deptrac      | Forbidden dependencies between layers                |
| `composer tests`                 | PHPUnit      | Incorrect behaviour                                  |
| `composer mutation-testing`      | Infection    | Tests that are too weak to notice a behaviour change |

You can run each step on its own with the commands above.

### Refactoring

`./tools/composer refactoring` rewrites the code for you, for example to make sure you leverage modern PHP syntax:
it runs Rector to apply automated refactorings and then PHP-CS-Fixer to fix the coding style of the result.
Review the changes before committing them.

### Structure

The code follows a Domain-Driven Design layering.
The architecture rules are enforced by Deptrac (see `deptrac.yaml`):
the `Domain` layer is the heart of the system and must not depend on any other layer.
In particular, it must never reach into `Infrastructure`.

```
src/
├── Domain/          # Entities, value objects, aggregates, domain services
│   └── SeatNumber   # Fully implemented reference value object
├── Application/     # Use cases that orchestrate the domain
└── Infrastructure/  # Adapters to the outside world (persistence, payment, ...)

tests/
├── Unit/            # Fast, isolated tests for the domain and application
├── Integration/     # Tests that exercise infrastructure adapters
└── Architecture/    # Additional architecture tests, if any
```

### The reference value object

`Thephpcc\EventFlow\Domain\SeatNumber` and its test `Thephpcc\EventFlow\Domain\SeatNumberTest` are implemented for you on purpose.
They are the template for the style in which everything else in this project is written:
immutable value objects, named constructors, behaviour-focused tests, and 100% mutation coverage.
Read them first.
