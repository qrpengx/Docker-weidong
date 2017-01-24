FROM daocloud.io/centos:7
RUN cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime
RUN yum -y update && \
yum -y install epel-release && \
yum -y install gearmand && \
yum clean all
RUN ln -sf /dev/stdout /var/log/gearman.log
EXPOSE 4730
ENTRYPOINT [ "gearmand" ]
