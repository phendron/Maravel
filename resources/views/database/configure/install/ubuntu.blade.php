<info>
<h2>Ubuntu</h2>
<pre>
<code>
if ! [[ "18.04 20.04 22.04" == *"$(lsb_release -rs)"* ]];
then
    echo "Ubuntu $(lsb_release -rs) is not currently supported.";
    exit;
fi

sudo su
curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -

curl https://packages.microsoft.com/config/ubuntu/$(lsb_release -rs)/prod.list > /etc/apt/sources.list.d/mssql-release.list

exit
sudo apt-get update
sudo ACCEPT_EULA=Y apt-get install -y msodbcsql18
# optional: for bcp and sqlcmd
sudo ACCEPT_EULA=Y apt-get install -y mssql-tools18
echo 'export PATH="$PATH:/opt/mssql-tools18/bin"' >> ~/.bashrc
source ~/.bashrc
# optional: for unixODBC development headers
sudo apt-get install -y unixodbc-dev
</code>	
</pre>
	
<install>
<h2>Installing on Ubuntu</h2>
<p>Ubuntu versions 18.04, 20.04, 21.04 and 21.10 are supported.</p>

 Note

<p>To install PHP 7.4 or 8.0, replace 8.1 with 7.4 or 8.0 in the following commands.</p>

<h3>Step 1. Install PHP (Ubuntu)</h3>
Bash

<pre>
<code>
sudo su
add-apt-repository ppa:ondrej/php -y
apt-get update
apt-get install php8.1 php8.1-dev php8.1-xml -y --allow-unauthenticated
</code>
</pre>
	
<h3>Step 2. Install prerequisites (Ubuntu)</h3>
<p>Install the ODBC driver for Ubuntu by following the instructions on the Install the Microsoft ODBC driver for SQL Server (Linux). Make sure to also install the unixodbc-dev package. It's used by the pecl command to install the PHP drivers.</p>
	
<h3>Step 3. Install the PHP drivers for Microsoft SQL Server (Ubuntu)</h3>
Bash

<pre>
<code>
sudo pecl install sqlsrv
	
sudo pecl install pdo_sqlsrv
sudo su
printf "; priority=20\nextension=sqlsrv.so\n" > /etc/php/8.1/mods-available/sqlsrv.ini
printf "; priority=30\nextension=pdo_sqlsrv.so\n" > /etc/php/8.1/mods-available/pdo_sqlsrv.ini
exit
sudo phpenmod -v 8.1 sqlsrv pdo_sqlsrv
</code>
</pre>
	
<p>If there is only one PHP version in the system, then the last step can be simplified to phpenmod sqlsrv pdo_sqlsrv.</p>

<h3>Step 4. Install Apache and configure driver loading (Ubuntu)</h3>
Bash

<pre>
<code>
sudo su
apt-get install libapache2-mod-php8.1 apache2
a2dismod mpm_event
a2enmod mpm_prefork
a2enmod php8.1
exit
</code>
</pre>
	
<h3>Step 5. Restart Apache and test the sample script (Ubuntu)</h3>
Bash

<pre>
<code>
sudo service apache2 restart
</code>
</pre>
	
</install>	
</info>