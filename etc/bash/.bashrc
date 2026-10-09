# ~/.bashrc

case $- in
    *i*) ;;
      *) return;;
esac

HISTCONTROL=ignoreboth
shopt -s histappend
HISTSIZE=10000
HISTFILESIZE=20000
HISTTIMEFORMAT='%F %T '
PROMPT_COMMAND='history -a'

shopt -s checkwinsize

export PATH="$PATH:/web/bin:/web/vendor/bin"

export EDITOR=micro
export MICRO_CONFIG_HOME="$HOME/.config/micro"

if [ -x /usr/bin/dircolors ]; then
    test -r ~/.dircolors && eval "$(dircolors -b ~/.dircolors)" || eval "$(dircolors -b)"
    alias ls='ls --color=auto'
    alias grep='grep --color=auto'
fi

if [ -f ~/.bash_aliases ]; then
    . ~/.bash_aliases
fi

if ! shopt -oq posix; then
    if [ -f /usr/share/bash-completion/bash_completion ]; then
        . /usr/share/bash-completion/bash_completion
    elif [ -f /etc/bash_completion ]; then
        . /etc/bash_completion
    fi
fi

command -v composer >/dev/null && eval "$(composer completion bash)"
[ -f /web/bin/console ] && eval "$(php /web/bin/console completion bash)"

parse_git_branch() {
    git branch --show-current 2>/dev/null | sed 's/.*/(&)/'
}

PS1='\[\033[01;32m\]\u@\h\[\033[00m\]:\[\033[01;34m\]\w\[\033[31m\]$(parse_git_branch)\[\033[35m\]\$\[\033[00m\] '

case "$TERM" in
    xterm*|rxvt*) PS1="\[\e]0;\u@\h: \w\a\]$PS1" ;;
esac
