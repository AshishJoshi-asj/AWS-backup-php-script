How to create daily HDD snapshot of you AWS EC2 instance
---

It looks like a very required and trivial task. But there is not outof the box solution in AWS. But fortunately it is easy to setup with few steps.

### How to setup

1. Copy `backup.php` and `db.json` files into your server folder `/usr/local/ec2/'.
2. Set parameter in lines 5-11.
3. Download [AWS-PHP-SDK](https://github.com/aws/aws-sdk-php/releases) and put into foder `/usr/local/ec2/`. Put it so that `require 'aws/aws-autoloader.php';` points to correct file.
4. Make `backup.php` file executable with `chmod +x backup.php` and make sure PHP can write into `db.json`.
5. Now try to run file in command line `./backup.php`. Now you can add this command to cron.
