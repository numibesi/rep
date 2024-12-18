# rep: Semantic Repository

This repository has been developed as a [custom module](https://www.drupal.org/docs/develop/creating-modules) for [Drupal 10+](https://www.drupal.org/about/10) and implemented mainly in PHP. The module depends on an external API called HASCO/API (HASCO/API code is available at https://github.com/HADatAc/hascoapi). HASCO/Repo content is stored inside of the API's knowledge graph.  

* Developer: HADatAc.org community (http://hadatac.org)

## Deployment: 

rep deployment requires the availability of a Drupal instance (version 10 or above), and an user of this Drupal instance with adminstrative privileges. 

* upload rep code
  * in the admin menu, go to `Extend` > `Add New Module` > `Add from a URL`
  * paste the following link from github: `https://github.com/HADatAc/rep/archive/refs/heads/main.zip`
* upload module dependencies. See below a list of current rep dependencies:
  * <i>Key</i> (https://www.drupal.org/project/key)
* go to `Extend` and install both rep and its dependencies
* clear all Drupal caches
  * in the admin menu, go to `Configuration` > `Performance` > `Clear All Caches`  
 
## Configuration setup:

User needs to have administrative privileges on Drupal to be able to setup rep

* Step 1: setup secret key to connect to API
  * the secret key is a string used during the setup of the API. The secret key of rep and its API must be exactly the same
  * In rep, the key is added going to `[drupal_url]/admin/config/system/keys/add`
    * Provide a name that will be later selected in the rep configuration page
    * Select type `Authentication`
    * Select provider `Configuration`
* Step 2: setup rep
  * go to `Main Menu` > `Advanced` > `Configuration` (or alternativelly `[drupal_url]/admin/config/rep`)
    * Check whether or not you want rep search page to be the main page of your website
    * Provide a short name
    * Provide a full name - used as the website's title
    * Provide a domain URL - this is the base of the URIs for all the rep elements created in the current rep instance 
    * Provide a namespace for the domain
    * Provide a description for the website
    * Provide the base URL for the API -- this is the URL of the back-end machine hosting the API
    * Provide the name of the key used to create API tokens -- the API is not going to respond if the token is missing or is incorrect
* Step 3: setup rep's Knowledge Graph
  * go to `Main Menu` > `Advanced` > `Configuration` > `Manage Namespaces` (or alternativelly `[drupal_url]/admin/config/rep/namespace`)
    * verify that you can see a list of namespaces
    * for the namespaces with values for `Source URL`, verify if they have values for triples. If not, you need to select the `Reload Triples From All Namespaces With URL`
    * wait a while and press the refresh button of the browser to verify if the triples have been loaded
    * if needed, the triples can be deleted and reloaded again. Wait for the triples to be zeroed before reloading.   

## Usage:

Once the module is installed, rep options are going to be available under `main menu` > `Advanced`. Access to rep options depends on user permissions on Drupal. By default, an anonymous user of a rep repository has access to the `search` and `about` pages. 

## Upgrade (in Pantheon): 

* put website under maintenance (`Configuration` > `Maintenance Mode` > `Put site into maintenance mode`) 
* using Drupal Admin Menu's `Extend`, uninstall rep module
* clear caches
* using Pantheon's site maintenance, move website from git to sftp mode
* use sftp to remove module files under `/code/web/modules`
* use sftp to remove module files cached under `/tmp`
* clear caches
* using `Extend`, verify if rep module's code has been removed from the list of available modules
* remove website from maintenance
* put website under maintenance
* using `Extend`, upload new version of rep module 
* using `Extend`, install new rep
* clear caches
* remove website from maintenance
* restore rep configuration including key

  
