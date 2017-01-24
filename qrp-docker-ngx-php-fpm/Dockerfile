FROM daocloud.io/centos:7
#install Package
ADD CentOS-Base.repo /etc/yum.repos.d/
RUN yum install -y wget vim
RUN wget -O /etc/yum.repos.d/epel.repo http://mirrors.aliyun.com/repo/epel-7.repo
RUN yum install -y openssh-server lrzsz unzip screen htop net-tools bind-utils python-pip
RUN yum install -y bison flex re2c libjpeg libjpeg-devel libpng libpng-devel freetype freetype-devel gd gd-devel libxml2 libxml2-devel zlib zlib-devel glibc glibc-devel glib2 glib2-devel bzip2 bzip2-devel ncurses ncurses-devel curl curl-devel e2fsprogs e2fsprogs-devel krb5-devel libidn libidn-devel openssl openssl-devel openldap openldap-devel nss_ldap pcre pcre-devel
RUN cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime
RUN mkdir -p ~/.pip
ADD pip.conf ~/.pip
ADD php.conf /etc/ld.so.conf.d
RUN pip install -U pip
RUN pip install -U supervisor

#set sshd
RUN ssh-keygen -q -N "" -t dsa -f /etc/ssh/ssh_host_dsa_key
RUN ssh-keygen -q -N "" -t rsa -f /etc/ssh/ssh_host_rsa_key
RUN ssh-keygen -t ecdsa -f /etc/ssh/ssh_host_ecdsa_key -N ""
RUN sed -ri 's/session    required     pam_loginuid.so/#session    required     pam_loginuid.so/g' /etc/pam.d/sshd
RUN mkdir -p /root/.ssh && chown root.root /root && chmod 700 /root/.ssh
RUN echo 'root:docker' | chpasswd

#set nginx
ADD nginx_el7.tar.gz /wdcloud/app
RUN useradd -u 1000 -s /sbin/nologin www
RUN mkdir -p /opt/proxy/temp
RUN mkdir -p /wdcloud/log/nginx
RUN chown -R www:www /wdcloud/log/nginx
RUN chown -R www:www /wdcloud/app/tengine

#set php-fpm
ADD php55_el7.tar.gz /wdcloud/app
RUN chown -R www.www /wdcloud/app/php55

ADD supervisord.conf /etc/supervisor/supervisord.conf
RUN mkdir -p /var/log/supervisor
EXPOSE 22 80
CMD ["supervisord"]
