COMM=$(c)
VERTAG=$(v)

install:
	composer install

lint:
	composer run-script phpcs -- --standard=PSR12 src bin

lint-fix:
	composer run-script phpcbf -- --standard=PSR12 src bin

reload:
	composer dump-autoload -o

git: gitadd gitcom gitpush	

gitadd:
	git add -A .

gitcom:
	git commit -m "$(COMM)"

gitpush:
	git push

release:
	git tag v1.0.$(VERTAG)
	git push origin v1.0.$(VERTAG)
