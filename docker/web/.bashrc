umask 002

export TERM=xterm

if [ -x /usr/bin/dircolors ]; then
    test -r ~/.dircolors && eval "$(dircolors -b ~/.dircolors)" || eval "$(dircolors -b)"
    alias ls='ls --color=auto'

    alias grep='grep --color=auto'
    alias fgrep='fgrep --color=auto'
    alias egrep='egrep --color=auto'
fi

alias ll='ls -ahl'
alias ownr='chown -R www-data:www-data .'
alias csfix='bin/php-cs-fixer fix'
alias composer='php -d memory_limit=-1 /usr/local/bin/composer'
alias sf="php bin/console"

if [ -f ~/.bash_aliases ]; then
    . ~/.bash_aliases
fi
