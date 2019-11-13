var GoogleAuth;

var SCOPE = 'https://www.googleapis.com/auth/drive.readonly';
// increase scope for admin users
if(google_scope === 'admin') {
  SCOPE = 'https://www.googleapis.com/auth/drive';
}

var googlePageToken = "";

function handleClientLoad() {
  // Load the API's client and auth2 modules.
  // Call the initClient function after the modules load.
  gapi.load('client:auth2', initClient);
}

function initClient() {
  // Retrieve the discovery document for version 3 of Google Drive API.
  // In practice, your app can retrieve one or more discovery documents.
  var discoveryUrl = 'https://www.googleapis.com/discovery/v1/apis/drive/v3/rest';

  // Initialize the gapi.client object, which app uses to make API requests.
  // Get API key and client ID from API Console.
  // 'scope' field specifies space-delimited list of access scopes.
  gapi.client.init({
      'apiKey': apiKey,
      'discoveryDocs': [discoveryUrl],
      'clientId': clientId,
      'scope': SCOPE
  }).then(function () {
    GoogleAuth = gapi.auth2.getAuthInstance();

    // Listen for sign-in state changes.
    GoogleAuth.isSignedIn.listen(updateSigninStatus);

    // Handle initial sign-in state. (Determine if user is already signed in.)
    var user = GoogleAuth.currentUser.get();
    setSigninStatus();

    // Call handleAuthClick function when user clicks on
    //      "Sign In/Authorize" button.
    $('#sign-in-or-out-button').click(function() {
      handleAuthClick();
    });
    $('#revoke-access-button').click(function() {
      revokeAccess();
    });
  });
}

function handleAuthClick() {
  if (GoogleAuth.isSignedIn.get()) {
    // User is authorized and has clicked 'Sign out' button.
    GoogleAuth.signOut();
  } else {
    // User is not signed in. Start Google auth flow.
    GoogleAuth.signIn();
  }
}

function revokeAccess() {
  GoogleAuth.disconnect();
}

function setSigninStatus(isSignedIn) {
  var user = GoogleAuth.currentUser.get();
  var isAuthorized = user.hasGrantedScopes(SCOPE);
  if (isAuthorized) {
    $('#sign-in-or-out-button').html('Sign out');
    $('#revoke-access-button').css('display', 'inline-block');
    //$('#auth-status').html('You are currently signed in and have granted ' + 'access to this app.');
    $('#google-api-access').hide();

    if(google_search_type === "single") {
      searchDriveFolder();
    }
    if(google_search_type === "table") {
      searchDriveFolderTable();
    }
    if(google_search_type === "all") {
      allProjectFolders();
    }
    if(google_search_type === "parents") {
      listParentFolders();
    }
  } else {
    $('#sign-in-or-out-button').html('Sign in to Google');
    $('#revoke-access-button').css('display', 'none');
    $('#auth-status').html('to see ' + google_content);
    $('#google-api-access').show();
  }
}

function updateSigninStatus(isSignedIn) {
  setSigninStatus();
}

function validateDriveInfo() {
  if ($("#folderName").val() == "") {
    $("#errorValidate").html("You are missing a <strong>Folder Name</strong>. This field is required.");
    window.scrollTo(0, 0);
    $("#errorValidate").show();
    return false;
  } else if ($("#googleOwnerSelect").val() == "") {
    $("#errorValidate").html("You need to select a <strong>Project Owner</strong>. This selection is required.");
    window.scrollTo(0, 0);
    $("#errorValidate").show();
    return false;
  } else {
    $("#errorValidate").hide();
    return true;
  }
}
function createDriveFolder() {
  if (validateDriveInfo()) {
    var folderId = $("#googleOwnerSelect").val();
    gapi.client.drive.files.create({
      'supportsTeamDrives':"true",
      'name': $("#folderName").val(),
      'mimeType': 'application/vnd.google-apps.folder',
      'description': 'prm-project-folder',
      parents: [folderId]
    }).then(function(response) {
      $('#googleDriveModal').modal('hide');
      $(".google-link").attr("href", "https://drive.google.com/drive/folders/" + response.result.id).fadeIn().css("display","inline-block");
      $("#create-google-folder").hide();
    });
}
}
function searchDriveFolderTable() {
  var all_projects = [];
  $(".google-drive-table tbody tr").each(function( index ) {
    all_projects.push($(this));
  });

  getProjectFolder();
  function getProjectFolder() {
    var $project_row = all_projects.pop();
    var project_num = $project_row.find(".google-project-number").text();

    if(project_num !== '') {
    var google_link = $project_row.find(".google-folder");
    gapi.client.drive.files.list({
      "includeTeamDriveItems": true,
      "q": "name contains '" + project_num + "' and mimeType= 'application/vnd.google-apps.folder' and trashed = false and fullText contains 'prm-project-folder'",
      "supportsTeamDrives": true
    })
        .then(function(response) {
                // Handle the results here (response.result has the parsed body).
                if(response.result.files.length > 0) {
                google_link.attr("href", "https://drive.google.com/drive/folders/" + response.result.files[0].id).show();
              }
              if (all_projects.length) {
                //setTimeout(getProjectFolder, 2);
                getProjectFolder();
              }
              },
              function(err) { console.error("Execute error", err); });
        }
        else {
          if (all_projects.length) {
            getProjectFolder();
          }
        }
    }
}

function searchDriveFolder() {
    gapi.client.drive.files.list({
      "includeTeamDriveItems": true,
      "q": "name contains '" + project_number + "' and mimeType= 'application/vnd.google-apps.folder' and trashed = false and fullText contains 'prm-project-folder'",
      "supportsTeamDrives": true
    })
        .then(function(response) {
                // Handle the results here (response.result has the parsed body).
                if(response.result.files.length > 0) {
                $(".google-link").attr("href", "https://drive.google.com/drive/folders/" + response.result.files[0].id).css("display", "block");
              }
              else {
                $("#create-google-folder").css("display", "block");
              }
              },
              function(err) { console.error("Execute error", err); });
}

function listParentFolders() {
    gapi.client.drive.files.list({
      "includeTeamDriveItems": true,
      "q": "trashed = false and mimeType= 'application/vnd.google-apps.folder' and fullText contains 'prm-parent-folder'",
      "supportsTeamDrives": true,
      "pageSize": 100,
      "fields": "files(id, name, parents)"
    })
        .then(function(response) {
                // Handle the results here (response.result has the parsed body).
                var files = response.result.files;
                var googleOwners = [];
                files.sort((a, b) => (a.name > b.name) ? 1 : -1);
                if (files && files.length > 0) {
                  for (var i = 0; i < files.length; i++) {
                    var file = files[i];

                    addToDriveSelect(file.id, file.parents[0]);

                    //$("#google-project-folders").append("<p class='paginate'><a href='https://drive.google.com/drive/folders/" + file.id + "' target='_blank'>" + file.name + "</p>");
                  }
                }
              },
              function(err) { console.error("Execute error", err); });
}
function allProjectFolders() {
    gapi.client.drive.files.list({
      "includeTeamDriveItems": true,
      "q": "(" + google_parents_query + ") and trashed = false and mimeType= 'application/vnd.google-apps.folder'",
      "supportsTeamDrives": true,
      "orderBy":"name",
      "pageToken": googlePageToken,
      "pageSize": 1000,
      "fields": "files(id, name, description), nextPageToken"
    })
        .then(function(response) {
                // Handle the results here (response.result has the parsed body).
                var files = response.result.files;
                files.sort((a, b) => (a.name > b.name) ? 1 : -1);
                if(response.result.nextPageToken){
                  googlePageToken = response.result.nextPageToken;
                  allProjectFolders();
                }
                if (files && files.length > 0) {
                  //$("#google-project-folders").html("");
                  for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    if(file.description === 'prm-project-folder') {
                    $("#google-project-folders").append("<p class='paginate'><a href='https://drive.google.com/drive/folders/" + file.id + "' target='_blank'>" + file.name + "</p>");
                  }
                  }
                  paginateResults();
                }
              },
              function(err) { console.error("Execute error", err); });
}

function paginateResults() {
var pageParts = $(".paginate");
var numPages = pageParts.length;
var perPage = 50;

pageParts.slice(perPage).hide();
// Apply simplePagination to our placeholder
$("#page-nav").pagination({
    items: numPages,
    itemsOnPage: perPage,
    cssStyle: "light-theme",
    onPageClick: function(pageNum) {
        var start = perPage * (pageNum - 1);
        var end = start + perPage;

        pageParts.hide()
                 .slice(start, end).show();
    }
});
}

function addToDriveSelect(fileId, driveId) {
  gapi.client.drive.drives.get({
      "driveId": driveId
    }).then(function(driveResponse) {
        // Handle the results here (response.result has the parsed body).
        if(ownerGoogleId === fileId) {
            $("#googleDriveSelect").append("<option value='" + fileId + "' selected='selected'>" + driveResponse.result.name + "</option>");
          }
          else {
              $("#googleDriveSelect").append("<option value='" + fileId + "'>" + driveResponse.result.name + "</option>");
            }
        },
        function(err) {
          console.error("Execute error", err);
          if(err.result.error.errors[0].reason === "userRateLimitExceeded") {
            setTimeout(function() {
              addToDriveSelect(fileId, driveId);
            }, 250);
        }
        });
}
