alias art='php artisan --ansi'
alias tinker='art tinker'
alias format='php vendor/bin/pint'
alias analyze='php vendor/bin/phpstan analyse'
alias test='php vendor/bin/paratest --coverage-html coverage'
alias stf='php vendor/bin/phpunit --filter'

# commit AI
function commit() {
   commitMessage="$*"

   git add .

   if [ "$commitMessage" = "" ]; then
      aicommits
      return
   fi

   eval "git commit -a -m '${commitMessage}'"
}

# function gfind
function gfind() {
    local excludeVendor="--exclude-dir=vendor"  # Default to excluding the vendor directory
    local searchString=""
    local searchPath="./"

    # Process all arguments
    for arg in "$@"; do
        if [[ "$arg" == "-w" || "$arg" == "--with-vendor" ]]; then
            excludeVendor=""  # Remove the exclude directive to include vendor
        elif [[ -z "$searchString" && "$arg" != -* ]]; then
            searchString="$arg"  # Set the search string if it's not a flag and is the first non-flag argument
        fi
    done

    # Check if a search string was provided
    if [[ -z "$searchString" ]]; then
        echo -e "${RED}Error: Missing required search string.${NC}"
        echo -e "${YELLOW}Usage: ${NC}gfind searchString [-w|--with-vendor]"
        return 1
    fi

    # Execute grep command
    grep --include=\*.php $excludeVendor -rnw $searchPath -e "$searchString"
}