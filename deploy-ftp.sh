source ./deploy-ftp.config.sh

# Build site
# npm run build

# creating package
rm -f package.zip
mv vendor ../
zip -r package.zip * -x .git installer.php deploy.sh package*.json LICENSE README.md $EXCLUDE
mv ../vendor ./
# transfer package
if [ "$FTP_PASS" = "" ];then
    echo "Password for $USER: "
    read -s FTP_PASS
fi
wput --binary package.zip ftp://$USER:$FTP_PASS@$FTP_HOST/$FTP_PATH/
wput installer.php ftp://$USER:$FTP_PASS@$FTP_HOST/$FTP_PATH/
curl $WEB_URL/installer.php

rm package.zip

