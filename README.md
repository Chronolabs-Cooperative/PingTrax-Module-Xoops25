# Pingtrax 2.07 ~ Pinging + Trackbacks + Sitemaps
## for XOOPS 2.5 ~ http://xoops.org
### (PHP Framework Example)
### Author: Simon Antony Roberts (AU) <simon@snails.email>

# Introduction:-

PingTrax is a module for XOOPS 2.5 which allows for the discovery of pages on the system for both pingback discovery and recrawling updates as well as sitemaps that are actually maintained on popularity as well as trackbacks (these are also known as permilinks).

The Trackbacks has two blocks which are generally displayed on all pages except the front one, one which displays the trackback URL, and the other for the trackback comments (This uses the XOOPS Comments System).

It will notify pingbacks with locations of information on your site as well as poll it when it is updated, this partly uses an extra smarty class for the trackbacking, make sure this is included, it is an extra plugin

## Configuration

You will need to change if you have installed, protector around, in the user agent permitted to crawl permission you need to include the following with the existing one to support all the crawlers you need to add these type where the pipe symbol section is:

    bot|Bot|BOT|pider|rawl|ink

## Requirements:-

 * You will require a system running XOOPS 2.5
 * You will require a site not running on localhost

## Downloads:-

You can download this module and others from sourceforge.net at the following project: https://sourceforge.net/projects/chronolabs/files/XOOPS%202.5/Modules/


