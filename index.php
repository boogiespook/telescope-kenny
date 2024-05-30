<?php
// Start the session
session_start();
if (!isset($_SESSION['toggle'])) {
	$_SESSION['toggle'] = false;
}

if (isset($_POST['toggle_submit'])) {
	$_SESSION['toggle'] = !$_SESSION['toggle'];
}

?>
<!DOCTYPE html>
  <html lang="en-us" class="pf-theme-dark">
    <head>
      <meta charSet="utf-8"/>
      <meta http-equiv="x-ua-compatible" content="ie=edge"/>
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
      <title data-react-helmet="true">CrowsNest Toggle</title>
      <link rel="stylesheet" href="css/brands.css" />
      <link rel="stylesheet" href="css/style.css" />
      <link rel="stylesheet" href="css/tabs.css" />
      <link rel="stylesheet" href="css/patternfly.css" />
      <link rel="stylesheet" href="css/patternfly-addons.css" />
    </head>

    <body>
<?php

$pg_host = getenv('POSTGRESQL_HOST');
$pg_db = getenv('POSTGRESQL_DATABASE');
$pg_user = getenv('POSTGRESQL_USER');
$pg_passwd = getenv('POSTGRESQL_PASSWORD');

$db_connection = pg_connect("host=$pg_host port=5432  dbname=$pg_db user=$pg_user password=$pg_passwd");
include 'functions.php';

?>    
    
    
    
<div class="pf-c-page">


  <header class="pf-c-page__header">
                <div class="pf-c-page__header-brand">
                  <div class="pf-c-page__header-brand-toggle">
                  </div>
                  <a class="pf-c-page__header-brand-link" href="index.php">
                  <img class="pf-c-brand" src="images/crowsnest-banner.png" alt="CrowsNest logo" />
                  </a>
                </div>


<?php
if (isset($_GET['profile'])){
$_SESSION['profile'] = $_GET['profile'];
$_SESSION['profileName'] = $_GET['name'];	
} else {
$_SESSION['profile'] = '1';
$_SESSION['profileName'] = "Core";	

}
?>

<?php
putProfileOptions();
?>

    <form method="post">
        <label>
<?php if($_SESSION['toggle'] == 1) { print "<span class='admin-mode'>Admin Mode</span>";} else {print "<span class='user-mode'>Dev Mode</span>";}  ?>
            <input id="toggle" type="hidden" name="toggle" <?php echo $_SESSION['toggle'] ? 'checked' : ''; ?>>
        </label>&nbsp
			<input class="switchUser" id="toggle" type="submit" name="toggle_submit" value=" <?php if($_SESSION['toggle'] == 1) { print "Switch to Developer";} else {print "Switch to Admin";} ?>">
    </form>


</header>


<main class="pf-c-page__main" tabindex="-1">  
    <section class="pf-c-page__main-section pf-m-full-height">
<div class="tabset">

<?php
if ($_SESSION['toggle'] == 1) {
	putAdminTabs();
} else {
	putUserTabs();
}
?>


  <div class="tab-panels">

<!--  Start of Dashboard -->  
    <section id="dashboard" class="tab-panel">

    <p id="dashboard" class="pf-c-title pf-m-3xl">Security Posture Overview (<?php print $_SESSION['profileName']; ?>)</p>

    <section class="pf-c-page__main-section pf-m-fill">
      <div class="pf-l-gallery pf-m-gutter">
<?php
## Get domains & capabilities

## Get domains & capabilities based on the profile
$profile = $_SESSION['profile'];

$chosenDomains = getDomainsByProfile($profile);
$i = 1;
foreach ($chosenDomains as $domain) {
$getDomains = "select domain.description, domain.id from domain WHERE domain.id = '" . $domain . "' ORDER BY domain.description;";
$domainResult = pg_query($getDomains) or die('Error message: ' . pg_last_error());

while ($row = pg_fetch_assoc($domainResult)) {
print '  
<div class="pf-c-card pf-m-selectable-raised pf-m-rounded" id="card-' . $i . '">
<div class="pf-c-card__header">';
putAperture($row['id']);
print '
</div>
<div class="pf-c-card__title">
            <p id="card-' . $i . '-check-label">'. $row['description'] . '</p>
            <div class="pf-c-content">
              <small>Key Capabilities</small>
            </div>
          </div>
          <div class="pf-c-card__body">
          <div class="pf-c-content">';
	$getCapabilities = "select capability.id as id, capability.description as capability, flag.description as flag from capability,flag where domain_id = '" . $row['id'] . "' and capability.flag_id = flag.id ORDER BY capability;";
	$capabilityResult = pg_query($getCapabilities) or die('Error message: ' . pg_last_error());
	while ($capRow = pg_fetch_assoc($capabilityResult)) {
       print putIcon($capRow['flag'], $capRow['capability']);
     }
       $i++;
print "</div></div></div>";
}
}

?>
</section>
<button  onClick="window.location.reload();" class="pf-c-button pf-m-primary" type="button">Refresh</button>
 </section>
  <!--  End of Dashboard -->  

    <!-- Start of Toggle -->    
    <section id="toggle" class="tab-panel">

<form id="toggle" class="pf-c-form" action="updateToggle.php" >
    <p class="pf-c-title pf-m-3xl">CrowsNest Toggle</p>
    <p>Items in <span class="blue">Blue</span> indicate that an integration is in place for that capability</p>
      <div class="pf-l-gallery pf-m-gutter">

<!-- CHANGE TO GET DYNAMIC NAMES -->
 <?php putToggleItems($profile); ?>
  <div class="pf-c-form__group pf-m-action">
    <div class="pf-c-form__actions">
      <button class="pf-c-button pf-m-primary" type="submit">Submit Updates</button>
    </div>
  </div>        
</form>  
  </section>
  <!-- End of Toggle -->     
  
    <section id="integrations" class="tab-panel">
<!-- Start of Integrations -->
    <p id="integrations" class="pf-c-title pf-m-2xl">Current Integrations</p>
<table class="pf-c-table pf-m-grid-lg" role="grid" aria-label="This is a sortable table example" id="table-sortable">
  <thead>
    <tr role="row">
      <th class="pf-c-table__sort pf-m-selected " role="columnheader" aria-sort="ascending" scope="col">
        <button class="pf-c-table__button">
          <div class="pf-c-table__button-content">
            <span class="pf-c-table__text">Capability</span>
          </div>
        </button>
      </th>
      <th class="pf-c-table__sort pf-m-help " role="columnheader" aria-sort="none" scope="col">
        <div class="pf-c-table__column-help">
          <button class="pf-c-table__button">
            <div class="pf-c-table__button-content">
              <span class="pf-c-table__text">Integration Name</span>
            </div>
          </button>
          <span class="pf-c-table__column-help-action">
          </span>
        </div>
      </th>
<th class="pf-c-table__sort pf-m-help " role="columnheader" aria-sort="none" scope="col">
        <div class="pf-c-table__column-help">
          <button class="pf-c-table__button">
            <div class="pf-c-table__button-content">
              <span class="pf-c-table__text">Success Criteria</span>
            </div>
          </button>
          <span class="pf-c-table__column-help-action">
          </span>
        </div>
      </th>
<th class="pf-c-table__sort pf-m-help " role="columnheader" aria-sort="none" scope="col">
        <div class="pf-c-table__column-help">
          <button class="pf-c-table__button">
            <div class="pf-c-table__button-content">
              <span class="pf-c-table__text">Last Update</span>
            </div>
          </button>
          <span class="pf-c-table__column-help-action">
          </span>
        </div>
      </th>      
      <th class="pf-c-table__sort pf-m-help " role="columnheader" aria-sort="none" scope="col">
        <div class="pf-c-table__column-help">
          <button class="pf-c-table__button">
            <div class="pf-c-table__button-content">
              <span class="pf-c-table__text">Integration Hash</span>
            </div>
          </button>
          <span class="pf-c-table__column-help-action">
          </span>
        </div>
      </th>
<th class="pf-c-table__sort pf-m-help " role="columnheader" aria-sort="none" scope="col">
        <div class="pf-c-table__column-help">
          <button class="pf-c-table__button">
            <div class="pf-c-table__button-content">
              <span class="pf-c-table__text">Delete</span>
            </div>
          </button>
          <span class="pf-c-table__column-help-action">
          </span>
        </div>
      </th>

 <th class="pf-c-table__sort pf-m-help " role="columnheader" aria-sort="none" scope="col">
        <div class="pf-c-table__column-help">
          <button class="pf-c-table__button">
            <div class="pf-c-table__button-content">
              <span class="pf-c-table__text"></span>
            </div>
          </button>
          <span class="pf-c-table__column-help-action">
          </span>
        </div>
      </th>        
    </tr>
  </thead>
  <tbody role="rowgroup">
<?php
$qq = "SELECT integrations.integration_id, capability.description as capability , integrations.integration_name, integrations.url as url, integrations.last_update as updated, success_criteria, integrations.hash as hash from capability,integrations WHERE integrations.capability_id = capability.id";
#print $qq;
$result = pg_query($qq) or die('Error message: ' . pg_last_error());

## Add to table
##       <td role="cell" data-label="updated"><button class="pf-c-button pf-m-primary pf-m-small" type="button">Run Integration</button></td>


while ($row = pg_fetch_assoc($result)) {
print '
    <tr role="row">
      <td role="cell" data-label="Capability">' . $row['capability'] . '</td>
      <td role="cell" data-label="Integration">' . $row['integration_name'] . '</td>
      <td role="cell" data-label="Success Criteria">' . $row['success_criteria'] . '</td>
      <td role="cell" data-label="updated">' . $row['updated'] . '</td>
      <td role="cell" data-label="updated">' . $row['hash'] . '</td>
      <td role="cell" data-label="deleteIntegration"> <a aria-label="Delete" href="delete.php?id=' . $row['integration_id'] . '&table=integrations&idColumn=integration_id" class="confirmation"> <i class="fa fa-trash"></i></a> </td>
    </tr>
';
}
?>
  </tbody>
</table>
<br>
<!--  Start of Add Integrations -->
    <p id="integrations" class="pf-c-title pf-m-2xl">Add Integration</p>

<form class="pf-c-form" action="addIntegration.php">
 <div class="pf-l-grid pf-m-all-6-col-on-md pf-m-gutter">

  <div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="horizontal-form-name">
        <span class="pf-c-form__label-text">Integration Name</span>
        <span class="pf-c-form__label-required" aria-hidden="true">&#42;</span>
      </label>
    </div>
    <div class="pf-c-form__group-control">
      <input class="pf-c-form-control" required type="text" id="integration-name" name="integration-name" aria-describedby="horizontal-form-name-helper2" />
    </div>
  </div>
  <div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="endpoint-url">
        <span class="pf-c-form__label-text">URL endpoint</span>
        <span class="pf-c-form__label-required" aria-hidden="true">&#42;</span>
      </label>
    </div>
    <div class="pf-c-form__group-control">
      <input class="pf-c-form-control" type="text" id="endpoint-url" name="endpoint-url" required/>
    </div>
  </div>
  <div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="capability-id">
        <span class="pf-c-form__label-text">Capability</span>
      </label>
    </div>
    <div class="pf-c-form__group-control">
      <select class="pf-c-form-control" id="capability-id" name="capability-id">
      <?php

$profile = $_SESSION['profile'];
      $qq = "select domain.description as domain, capability.description as capability, capability.id as capabilityId from domain,capability where domain.id = capability.domain_id;";
$result = pg_query($qq) or die('Error message: ' . pg_last_error());
while ($row = pg_fetch_assoc($result)) {
$str = $row['domain'] . " - " . $row['capability'];
print '
<option value="' . $row['capabilityid'] . '">' . $str . '</option>
';		
}
      ?>
     </select>
    </div>
  </div>
  <div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="integration_hash">
        <span class="pf-c-form__label-text">Integration Hash</span>
      </label>
    <p class="pf-c-form__helper-text" id="form-demo-grid-name-helper" aria-live="polite" >Use this hash in your integration code</p>
    </div>
    <div class="pf-c-form__group-control">
      <?php
		$chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
		$hash = substr(str_shuffle($chars), 0, 5);
		print "<input id=hash name=hash value=$hash readonly>";
      ?>

    </div>
  </div>

  <div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="username">
        <span class="pf-c-form__label-text">Username</span>
      </label>
    </div>
    <div class="pf-c-form__group-control">
      <input class="pf-c-form-control" type="text" id="username" name="username" />
    </div>
  </div>

 <div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="password">
        <span class="pf-c-form__label-text">Password</span>
      </label>
    </div>
    <div class="pf-c-form__group-control">
      <input class="pf-c-form-control" type="text" id="password" name="password" />
    </div>
  </div> 
 
<div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="token">
        <span class="pf-c-form__label-text">Token</span>
      </label>
    </div>
    <div class="pf-c-form__group-control">
<textarea class="pf-c-form-control" name="token" id="token"></textarea>    </div>
  </div>  

 <div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="success-criteria">
        <span class="pf-c-form__label-text">Success Criteria</span>
      </label>
    </div>
    <p
          class="pf-c-form__helper-text"
          id="form-demo-grid-name-helper"
          aria-live="polite"
        >Success criteria depends on the specific integration. For example it could be a number (such as a %) or boolean (true/false, yes/no)</p>
    <div class="pf-c-form__group-control">
      <input class="pf-c-form-control" type="text" id="success-criteria" name="success-criteria" required/>
    </div>
  </div> 

   <div class="pf-c-form__group pf-m-action">
    <div class="pf-c-form__actions">
      <button class="pf-c-button pf-m-primary" type="submit">Add Integration</button>
    </div>
  </div>  
</div>
</form>
    </section>
<!--  End of Add Integrations -->  
  
<!--  Start of Domains -->  
<section id="domains" class="tab-panel">
<div class="pf-l-grid pf-m-gutter">
  <div class="pf-l-grid__item pf-m-6-col">
<p class="pf-c-title pf-m-2xl">Domains</p>
<p class="pf-c-title pf-m-md"><span class="red">WARNING</span> - Deleting a domain will also delete all child capabilities</p>
<table class="pf-c-table pf-m-compact pf-m-grid-md" role="grid" id="table-sortable">
  <thead>
    <tr role="row">
      <th class="pf-c-table__sort pf-m-selected " role="columnheader" aria-sort="ascending" scope="col">
        <button class="pf-c-table__button">
          <div class="pf-c-table__button-content">
            <span class="pf-c-table__text">Domain</span>
          </div>
        </button>
      </th>     
      <th class="pf-c-table__sort pf-m-selected " role="columnheader" aria-sort="ascending" scope="col">
        <button class="pf-c-table__button">
          <div class="pf-c-table__button-content">
            <span class="pf-c-table__text">Delete Domain</span>
          </div>
        </button>
      </th>     
    </tr>
      </thead>
  <tbody role="rowgroup">
<?php
$qq = "select description,id from domain order by description";
$result = pg_query($qq) or die('Error message: ' . pg_last_error());

while ($row = pg_fetch_assoc($result)) {
print '
    <tr role="row">
      <td role="cell" data-label="method">' . $row['description'] . '</td>
      <td role="cell" data-label="deleteDomain"> <a aria-label="Delete" href="delete.php?id=' . $row['id'] . '&table=domain&idColumn=id" class="confirmation"> <i class="fa fa-trash"></i></a> </td>
    </tr>
';
}
?>
  </tbody>
</table>
<br>
<p id="integrations" class="pf-c-title pf-m-2l">Add Domain</p>
<form  class="pf-c-form" action="addDomain.php">
  <div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="horizontal-form-name">
        <span class="pf-c-form__label-text">Domain Name</span>
        <span class="pf-c-form__label-required" aria-hidden="true">&#42;</span>
      </label>
    </div>
    <div class="pf-c-form__group-control">
      <input class="pf-c-form-control" required type="text" id="domain" name="domain" aria-describedby="horizontal-form-name-helper2" />
    </div>
  </div>
     <div class="pf-c-form__group">
    <div class="pf-c-form__actions">
      <button class="pf-c-button pf-m-primary" type="submit">Add Domain</button>
    </div>
  </div>  
  </form>
  </div>
  </div>
</section>
<!--  End of Domains -->  

<!--  Start of Capabilities -->  
<section id="capabilities" class="tab-panel">
<div class="pf-l-grid pf-m-gutter">
  <div class="pf-l-grid__item pf-m-6-col">
<p class="pf-c-title pf-m-2xl">Capabilities</p>
<p class="pf-c-title pf-m-md">Use the tree structure below to view and delete capabilities</p>
<p><i>Note: You can't delete capabilities which have active integrations</i></p>
<br>

<?php
#$qq = "select capability.description as capability,capability.id as capabilityid,capability.domain_id,domain.description as domain from capability, domain WHERE domain.id = capability.domain_id order by domain";
$qq = "select description from domain order by description";
$result = pg_query($qq) or die('Error message: ' . pg_last_error());

while ($row = pg_fetch_assoc($result)) {
print '
<details class="details">
      <summary class="summary">' . $row['description'] . '</summary>
      <ul>';
$qq2 = "select capability.description as capability,capability.id as capabilityid,capability.domain_id,domain.description as domain from capability, domain WHERE domain.id = capability.domain_id and domain.description =  '" . $row['description'] . "' order by domain";      

$result2 = pg_query($qq2) or die('Error message: ' . pg_last_error());

while ($row2 = pg_fetch_assoc($result2)) {

## Check if there is an integration for the capability
$integrationQuery = "select count(*) as total from integrations where capability_id = '" . $row2['capabilityid'] . "'";

$integrationResult = pg_query($integrationQuery) or die('Error message: ' . pg_last_error());
$intCount = pg_fetch_assoc($integrationResult);

if ($intCount['total'] > 0) {
print '<li><span role="cell" data-label="deleteCapability"><i class="fa fa-times"></i></span>&nbsp;&nbsp' . $row2['capability'] . '</li>';
#$toggleClass = "toggle-capability-integration";
} else {
print '<li><span role="cell" data-label="deleteCapability"> <a aria-label="Delete" href="delete.php?id=' . $row2['capabilityid'] . '&table=capability&idColumn=id" class="confirmation"> <i class="fa fa-trash"></i></a></span>&nbsp;&nbsp' . $row2['capability'] . '</li>';
#$toggleClass = "toggle-capability";
}	
	
   }
   print "</ul></details>";
}
?>


</div>

<div class="pf-l-grid__item pf-m-6-col">
<p id="capabilities" class="pf-c-title pf-m-2l">Add Capability</p>
<form  class="pf-c-form" action="addCapability.php">
  <div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="horizontal-form-name">
        <span class="pf-c-form__label-text">Capability Name</span>
        <span class="pf-c-form__label-required" aria-hidden="true">&#42;</span>
      </label>
    </div>
    <div class="pf-c-form__group-control">
      <input class="pf-c-form-control" required type="text" id="capability" name="capability" aria-describedby="horizontal-form-name-helper2" />
    </div>
  </div>
  <div class="pf-c-form__group-control">
  <label class="pf-c-form__label" for="horizontal-form-name">
        <span class="pf-c-form__label-text">Select Domain</span>
      </label>
      <select class="pf-c-form-control" id="domainId" name="domainId">
      <?php
      $qq = "select description,id from domain order by description;";
$result = pg_query($qq) or die('Error message: ' . pg_last_error());
while ($row = pg_fetch_assoc($result)) {
$str = $row['description'];
print '
<option value="' . $row['id'] . '">' . $str . '</option>
';		
}
      ?>
     </select>
    </div>
     <div class="pf-c-form__group">
    <div class="pf-c-form__actions">
      <button class="pf-c-button pf-m-primary" type="submit">Add Capability</button>
    </div>
  </div>  
  
  </form>
</section>
<!--  End of Capabilities -->  

<!--  Start of Profiles -->  
<section id="profiles" class="tab-panel">
<div class="pf-l-grid pf-m-gutter">
  <div class="pf-l-grid__item pf-m-6-col">
<p class="pf-c-title pf-m-2xl">Profiles</p>

<table class="pf-c-table pf-m-compact pf-m-grid-md" role="grid" id="table-sortable">
  <thead>
    <tr role="row">
      <th class="pf-c-table__sort pf-m-selected " role="columnheader" aria-sort="ascending" scope="col">
        <button class="pf-c-table__button">
          <div class="pf-c-table__button-content">
            <span class="pf-c-table__text">Profile</span>
          </div>
        </button>
      </th>     
      <th class="pf-c-table__sort pf-m-selected " role="columnheader" aria-sort="ascending" scope="col">
        <button class="pf-c-table__button">
          <div class="pf-c-table__button-content">
            <span class="pf-c-table__text">Delete Profile</span>
          </div>
        </button>
      </th>     
    </tr>
      </thead>
  <tbody role="rowgroup">
<?php
$qq = "select name,id from profiles order by id asc";
$result = pg_query($qq) or die('Error message: ' . pg_last_error());

while ($row = pg_fetch_assoc($result)) {
print '
    <tr role="row">
      <td role="cell" data-label="method">' . $row['name'] . '</td>
      <td role="cell" data-label="deleteDomain"> <a aria-label="Delete" href="delete.php?id=' . $row['id'] . '&table=profiles&idColumn=id" class="confirmation"> <i class="fa fa-trash"></i></a> </td>
    </tr>
';
}
?>
  </tbody>
</table>
</div>
<div class="pf-l-grid__item pf-m-6-col">
<p id="profiles" class="pf-c-title pf-m-2l">Add Profile</p>
<form  class="pf-c-form" action="addProfile.php">
  <div class="pf-c-form__group">
    <div class="pf-c-form__group-label">
      <label class="pf-c-form__label" for="horizontal-form-name">
        <span class="pf-c-form__label-text">Profile Name</span>
        <span class="pf-c-form__label-required" aria-hidden="true">&#42;</span>
      </label>
    </div>
    <div class="pf-c-form__group-control">
      <input class="pf-c-form-control" required type="text" id="profileName" name="profileName" aria-describedby="horizontal-form-name-helper2" />
    </div>
  </div>
    <fieldset>  
    <legend>Select Domains to form the Profile</legend>  
<?php
## Get all the domains and add them as checkboxes
getDomainsForProfiles();
?>
    </fieldset>    
     <div class="pf-c-form__group">
    <div class="pf-c-form__actions">
      <button class="pf-c-button pf-m-primary" type="submit">Add Profile</button>
    </div>
  </div>  
  </form>
  </div>
  </div>
  </div>
</section>
<!--  End of Profiles -->  


  </div>
</div>
</div>


  <!--  End of Capabilities -->  

  </main>
</div>   

  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
<script type="text/javascript">
    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure you want to delete this entry ?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }
</script>    
<script type="text/javascript" >
$("form").submit(function () {

    var this_master = $(this);

    this_master.find('input[type="checkbox"]').each( function () {
        var checkbox_this = $(this);


        if( checkbox_this.is(":checked") == true ) {
            checkbox_this.attr('value','1');
        } else {
            checkbox_this.prop('checked',true);
            //DONT' ITS JUST CHECK THE CHECKBOX TO SUBMIT FORM DATA    
            checkbox_this.attr('value','0');
        }
    })
})
</script>

<script type="text/javascript">
    document.getElementById('switch').addEventListener('change', function () {
        this.form.submit();
    });
</script>
   
  </body>
</html>
