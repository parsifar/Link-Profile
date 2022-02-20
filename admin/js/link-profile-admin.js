"use strict";
/**
 * All of the code for your admin-facing JavaScript source
 * should reside in this file.
 *
 */

document.addEventListener("DOMContentLoaded", function () {
  let incomingTable = document.querySelector(".incoming-table");
  let outgoingTable = document.querySelector(".outgoing-table");
  let incomingMessage = document.querySelector(".incoming-message ");
  let outgoingMessage = document.querySelector(".outgoing-message");
  let incomingTableBody = document.getElementById("incoming-results");
  let outgoingTableBody = document.getElementById("outgoing-results");
  let loaderanimation = document.getElementById("loader-image");
  let postSelectForm = document.getElementById("link-profile-form");
  let selectedPostField = document.getElementById("selected-post-input");

  //form submit event
  postSelectForm.addEventListener("submit", function (e) {
    e.preventDefault();
    //show the loader animation
    showElement(loaderanimation);

    incomingTableBody.innerHTML = "";
    outgoingTableBody.innerHTML = "";
    hideElement(incomingTable);
    hideElement(outgoingTable);
    hideElement(incomingMessage);
    hideElement(outgoingMessage);

    //set the data to be sent
    let postData = new FormData();
    postData.append("action", "find_incoming_links");
    postData.append("selected_post_id", allPostsArray[selectedPostField.value]);
    const ajaxParams = new URLSearchParams(postData);

    //send the AJAX request
    fetch(ajaxurl, {
      method: "POST",
      credentials: "same-origin",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },

      body: ajaxParams,
    })
      .then((response) => response.json())
      .then((data) => {
        //data returned from the backend
        hideElement(loaderanimation);
        // if there is incoming links then populate incoming links message and table
        if (data["incoming_links"].length > 0) {
          incomingMessage.textContent = `${data["incoming_links"].length} posts link to ${selectedPostField.value}`;
          showElement(incomingMessage);

          //populate the incoming table
          data["incoming_links"].forEach((row, i) => {
            incomingTableBody.insertAdjacentHTML(
              "beforeend",
              `<tr> <td>${i + 1}</td> <td><span class="post_type badge ${
                row.post_type
              }">${row.post_type}</span></td> <td><a target="_blank" href=${
                row.permalink
              }>${row.title}</a></td> <td>${row.number}</td> <td>${row.anchors
                .map(
                  (anchor) =>
                    '<span class="badge anchor bg-secondary">' +
                    anchor +
                    "</span>"
                )
                .join(" ")}</td> </tr>`
            );
          });
          showElement(incomingTable);
        } else {
          //if there is no incoming link
          incomingMessage.textContent = "No incoming link found!";
          showElement(incomingMessage);
        }

        // if there is outgoing links then populate outgoing links message and table
        if (data["outgoing_links"].length > 0) {
          outgoingMessage.textContent = `${selectedPostField.value} has ${data["outgoing_links"].length} outgoing links.`;
          showElement(outgoingMessage);

          //populate the outgoing table
          data["outgoing_links"].forEach((row, i) => {
            outgoingTableBody.insertAdjacentHTML(
              "beforeend",
              `<tr> <td>${i + 1}</td> <td><span class="link_type ${
                row.link_type
              } badge">${
                row.link_type
              }</span></td> <td><a target="_blank" href=${row.href}>${
                row.href
              }</a></td> <td><span class="badge anchor bg-secondary">${
                row.anchor
              }</span></td> </tr>`
            );
          });

          showElement(outgoingTable);
        } else {
          outgoingMessage.textContent = "No outgoing link found!";
          showElement(outgoingMessage);
        }
      })
      .catch((err) => {
        console.log(err);
      });
  }); //submit
});

//utility functions to show and hide elements
function hideElement(selector) {
  selector.style.display = "none";
}

function showElement(selector) {
  if (selector.tagName == "TABLE") {
    selector.style.display = "table";
  } else {
    selector.style.display = "block";
  }
}
