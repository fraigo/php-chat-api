export FTP_HOST=ftp.marketienda.com
export FTP_PATH=coreapps.info/projects/php-chat
export WEB_URL=http://coreapps.info/projects/php-chat
export USER=francisco@marketienda.com
export EXCLUDE=

# Build site
# npm run build

# creating package
rm -f package.zip
zip -r package.zip * -x .git installer.php deploy.sh package*.json LICENSE README.md $EXCLUDE

# transfer package
if [ "$FTP_PASS" = "" ];then
    echo "Password for $USER: "
    read -s FTP_PASS
fi
wput --binary package.zip ftp://$USER:$FTP_PASS@$FTP_HOST/$FTP_PATH/
wput installer.php ftp://$USER:$FTP_PASS@$FTP_HOST/$FTP_PATH/
curl $WEB_URL/installer.php

rm package.zip

