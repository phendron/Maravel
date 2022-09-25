// Laravel Database OS Specific Package Repositories
	
var package = function(name="", download=true, url=false, os="", distro="", version=false, arch=64, supported=true, command=false, cmd=false){
	this.name = name;
	this.download = download;
	this.url = url;
	this.os = os;
	this.distro = distro;
	this.version = version;
	this.arch = arch;
	this.supported = supported;
	this.command = command;
	this.cmd = cmd;
};

var packages = function(distros=[]){
	this.distros = distros;
	this.filtered_distros = false;
	
	this.add = function(distro_package){
		this.distros.push(distro_package);
	}
	
	this.getPackagesByOS = function(OS=""){
		if(!this.filtered_distros){this.filtered_distros = Array.from(this.distros);}
		
		for(var i = this.filtered_distros.length-1; i >= 0; i--){
			var distro_package = this.filtered_distros[i];
			if(distro_package.os!=OS){
				this.filtered_distros.splice(i, 1);
			}
		}
		
		var found_distros = this.filtered_distros;
		this.filtered_distros=false;
		return new packages(found_distros);
	}
	
	this.getPackagesByName = function(Name=""){
		if(!this.filtered_distros){this.filtered_distros = Array.from(this.distros);}
		
		for(var i = this.filtered_distros.length-1; i >= 0; i--){
			var distro_package = this.filtered_distros[i];
			if(distro_package.name!=Name){
				this.filtered_distros.splice(i, 1);
			}
		}
		
		var found_distros = this.filtered_distros;
		this.filtered_distros=false;
		return new packages(found_distros);
	}
	
	this.clearFilter = function(){
		this.filtered_distros = false;
	}
	
};

var install_packages = new packages();

/* SQLite Package */
/* MySQL Package */
install_packages.add(new package('sqlite', false, '', 'Windows', 'x86', false, 64, true, false, false));
install_packages.add(new package('sqlite', false, '', 'Max', 'OSX', true, false, 64, false, false));
install_packages.add(new package('sqlite', false, '', 'Linux', 'Ubuntu', false, 64, true, false, false));
install_packages.add(new package('sqlite', false, '', 'Linux', 'Debian', false, 64, true, false, false));
install_packages.add(new package('sqlite', false, '', 'Linux', 'Redhat', false, 64, true, false, false));
install_packages.add(new package('sqlite', false, '', 'Linux', 'Suse', false, 64, true, false, false));

		
/* PostgreSQL Package */
install_packages.add(new package('postgresql',true, 'https://www.enterprisedb.com/postgresql-tutorial-resources-training?uuid=db55e32d-e9f0-4d7c-9aef-b17d01210704', 'Windows','11', false, 64, true, false, false));
install_packages.add(new package('postgresql',true, 'https://www.enterprisedb.com/postgresql-tutorial-resources-training?uuid=f027c016-7c5b-43fd-beb7-59ee43135607', 'Mac', 'OSX', false, 64, true, false, false));
install_packages.add(new package('postgresql', false, false, 'Linux', 'Ubuntu', false, 64, true, false, false));
install_packages.add(new package('postgresql', false, false, 'Linux', 'Debian', false, 64, true, false, false));
install_packages.add(new package('postgresql', false, false, 'Linux', 'Redhat', false, 64, true, false, false));
install_packages.add(new package('postgresql', false, false, 'Linux', 'Suse', false, 64, true, false, false));
		
/* MySQL Package */
install_packages.add(new package('mysql', true, 'https://dev.mysql.com/get/Downloads/MySQLInstaller/mysql-installer-web-community-8.0.30.0.msi', 'Windows', 'x86', false, 64, true, false, false));
install_packages.add(new package('mysql', true, 'https://dev.mysql.com/downloads/file/?id=512619', 'Max', 'OSX', true, false, 64, false, false));
install_packages.add(new package('mysql', true, 'https://dev.mysql.com/downloads/file/?id=512284', 'Linux', 'Ubuntu', false, 64, true, false, false));
install_packages.add(new package('mysql', true, 'https://dev.mysql.com/downloads/file/?id=512372', 'Linux', 'Debian', false, 64, true, false, false));
install_packages.add(new package('mysql', true, 'https://dev.mysql.com/downloads/file/?id=512536', 'Linux', 'Redhat', false, 64, true, false, false));
install_packages.add(new package('mysql', true, 'https://dev.mysql.com/downloads/file/?id=512416', 'Linux', 'Suse', false, 64, true, false, false));

/* MariaDB Package */
install_packages.add(new package('mariadb', true, 'https://mirrors.xtom.nl/mariadb/mariadb-10.9.3/winx64-packages/mariadb-10.9.3-winx64.msi', 'Windows', '11', false, 64, true, false, false));
install_packages.add(new package('mariadb', true, 'https://mirrors.xtom.nl/mariadb/mariadb-10.9.3/bintar-linux-systemd-x86_64/mariadb-10.9.3-linux-systemd-x86_64.tar.gz', 'Linux', 'x86', false, 64, true, false, false));
		
/* Microsoft SQL Package */
/* MySQL Package */
install_packages.add(new package('mssql', true, 'https://go.microsoft.com/fwlink/?linkid=2199011', 'Windows', '11', false, 64, true, false, false));
install_packages.add(new package('mssql', true, 'https://download.microsoft.com/download/b/9/f/b9f3cce4-3925-46d4-9f46-da08869c6486/msodbcsql18_18.1.1.1-1_amd64.apk', 'Linux', 'Alpine', false, 64, true, false, false));
install_packages.add(new package('mssql', true, 'https://packages.microsoft.com/config/ubuntu/22.04/prod.list', 'Linux', 'Ubuntu', false, 64, true, false, false));
install_packages.add(new package('mssql', true, 'https://packages.microsoft.com/config/debian/9/prod.list', 'Linux', 'Debian', false, 64, true, false, false));
install_packages.add(new package('mssql', true, 'https://packages.microsoft.com/config/rhel/7/prod.repo', 'Linux', 'Redhat', false, 64, true, false, false));
install_packages.add(new package('mssql', true, 'https://packages.microsoft.com/config/sles/11/prod.repo', 'Linux', 'Suse', false, 64, true, false, false));