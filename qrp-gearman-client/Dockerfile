FROM daocloud.io/centos:7
ADD CentOS-Base.repo /etc/yum.repos.d/
ADD epel-7.repo /etc/yum.repos.d/
RUN cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime
ADD cron.conf /cron/crontab
RUN mkdir -p /wdcloud/app/
ADD php.conf /etc/ld.so.conf.d
#install 
RUN yum -y install gd libgearman libgearman-devel python-pip
RUN yum clean all
RUN mkdir -p ~/.pip
ADD pip.conf ~/.pip
RUN pip install -U pip
RUN pip install devcron
ADD php55.tar.gz /wdcloud/app/
ADD gearman-client.tar.gz /wdcloud/app/
CMD ["devcron.py", "/cron/crontab"]
