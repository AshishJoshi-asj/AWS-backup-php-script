How to create daily HDD/EBS snapshot of you AWS EC2 instance
---

It looks like a very required and trivial task. But there is not outof the box solution in AWS. But fortunately it is easy to setup with few steps.

### How to setup

1. Opent SSH connection to your server.
2. Navigate to folder 
   
        $ cd /usr/local/

3. Clon this gist
   
        $ git clone https://gist.github.com/9738785.git ec2

4. Go to that folder
   
        $ cd ec2

5. Make `backup.php` executable
   
        $ chmod +x backup.php
       
6. Open [releases](https://github.com/aws/aws-sdk-php/releases) of the AWS PHP SDK github project and copy URL of `aws.zip` button. Now download it into your server.

        $ wget https://github.com/aws/aws-sdk-php/releases/download/2.6.0/aws.zip

7. Unzip this file into `aws` directory.

        $ unzip aws.zip -d aws 

8. Edit `backup.php` php file and set all settings in line `5-12`

        $dryrun     = FALSE;
        $interval   = '24 hours';
        $keep_for   = '10 Days';
        $volumes    = array('vol-********');
        $api_key    = '*********************';
        $api_secret = '****************************************';
        $ec2_region = 'us-east-1';
        $snap_descr = "Daily backup";

9. Test it. Run this script

        $ ./backup.php
   
   Test is snapshot was created. 

10. If everything is ok just add cronjob.

        * 23 * * * /usr/local/ec2/backup.php
