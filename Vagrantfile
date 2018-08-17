# -*- mode: ruby -*-
# vi: set ft=ruby :

require File.dirname(__FILE__)+"/vagrant-dependency-manager"
check_plugins ["vagrant-hostmanager"]

Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/xenial64"
  config.vm.hostname = "monzo-to-ynab.local"
  config.vm.network "private_network", ip: "192.168.45.45"

  config.vm.provider "virtualbox" do |vb|
     vb.memory = "1024"
  end

  config.vm.provision "shell", path: "provision.sh"
end
