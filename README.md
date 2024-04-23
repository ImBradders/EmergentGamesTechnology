# Emergent Games Technology - Communication

This repository was created alongside a lecture for GDEV60016 - Emergent Games Technology. It demonstrates a number of different methods of communcation between Unreal Engine Blueprints and other programs, sometimes running on separate hardware. This README discuses how you might be able to set this up yourself to use or play around with.

# Applications

This repository was developed using:
- Unreal Engine 5.2.1
- Visual Studio Community 2022
- .NET 7.0
- C# 11
- Php 8.1.2
- Apache 2.4.52

# Setup

## Socket Communication

To setup the game to allow for the socket communication aspects to work, you will need to open the visual studio project and run the "server" that the game connects to before you step on the pad in game. You are free to set this up on a separate computer, but you will need to change the ip address in BP_Socket Blueprint to make this work. You should also be able to set this up to communcate via a computer on a different network - you will have to forward ports to make this work as expected.

## REST API Communication

To setup the game to allow for the REST API communication aspects to work, you will need to host a webserver.

The easiest way to do this is host one on your local computer - XAMPP is a great application to do this with. You can also host this on a remote server - this will likely run Linux. Linux doesn't have access to Xampp so you will have to install Apache and Php manually.

Make sure that you have the .htaccess file and php files are placed at the root of the /var/www/html directory and that you create the /var/www/data directory that is required for the application. Furthermore, you will need to adjust the Apache settings to allow the rewrite engine module.

