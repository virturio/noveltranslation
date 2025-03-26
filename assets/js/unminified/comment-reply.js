/**
 * Handles the addition of the comment form.
 *
 * @since 2.7.0
 * @output wp-includes/js/comment-reply.js
 *
 * @namespace addComment
 *
 * @type {Object}
 */
window.addComment = (function (window) {
  // Avoid scope lookups on commonly used letiables.
  let document = window.document;

  // Settings.
  let config = {
    commentReplyClass: "comment-reply-link",
    cancelReplyId: "cancel-comment-reply-link",
    commentFormId: "commentform",
    commentFormAuthorFieldId: "author",
    commentFormEmailFieldId: "email",
    commentFieldId: "comment-field",
    temporaryFormId: "wp-temp-form-div",
    parentIdFieldId: "comment_parent",
    postIdFieldId: "comment_post_ID",
    cookiesConsentId: "wp-comment-cookies-consent",
    commentFormAuthorFieldsId: "comment-form-author-fields",
  };

  // Check browser cuts the mustard.
  let cutsTheMustard =
    "querySelector" in document && "addEventListener" in window;

  /*
   * Check browser supports dataset.
   * !! sets the letiable to true if the property exists.
   */
  let supportsDataset = !!document.documentElement.dataset;

  // For holding the cancel element.
  let cancelElement;

  // For holding the comment form element.
  let commentFormElement;

  // For holding the comment field element.
  let commentFieldElement;

  // The respond element.
  let respondElement;

  // The cookies consent element.
  let cookiesConsentElement;

  if (cutsTheMustard && document.readyState !== "loading") {
    init();
  } else if (cutsTheMustard) {
    window.addEventListener("DOMContentLoaded", init, false);
  }

  /**
   * Add events to links classed .comment-reply-link.
   *
   * Searches the context for reply links and adds the JavaScript events
   * required to move the comment form. To allow for lazy loading of
   * comments this method is exposed as window.commentReply.init().
   *
   * @since 1.0.0
   *
   * @memberOf addComment
   *
   * @param {HTMLElement} context The parent DOM element to search for links.
   */
  function init(context) {
    if (!cutsTheMustard) {
      return;
    }

    // Get required elements.
    cancelElement = getElementById(config.cancelReplyId);
    commentFormElement = getElementById(config.commentFormId);
    commentFieldElement = getElementById(config.commentFieldId);
    cookiesConsentElement = getElementById(config.cookiesConsentId);

    // No cancel element, no replies.
    if (!cancelElement) {
      return;
    }

    cancelElement.addEventListener("touchstart", cancelEvent);
    cancelElement.addEventListener("click", cancelEvent);

    onInitFunction();

    let links = replyLinks(context);
    let element;

    for (let i = 0, l = links.length; i < l; i++) {
      element = links[i];

      element.addEventListener("touchstart", clickEvent);
      element.addEventListener("click", clickEvent);
    }
  }

  /**
   * Return all links classed .comment-reply-link.
   *
   * @since 1.0.0
   *
   * @param {HTMLElement} context The parent DOM element to search for links.
   *
   * @return {HTMLCollection|NodeList|Array}
   */
  function replyLinks(context) {
    let selectorClass = config.commentReplyClass;
    let allReplyLinks;

    // childNodes is a handy check to ensure the context is a HTMLElement.
    if (!context || !context.childNodes) {
      context = document;
    }

    if (document.getElementsByClassName) {
      // Fastest.
      allReplyLinks = context.getElementsByClassName(selectorClass);
    } else {
      // Fast.
      allReplyLinks = context.querySelectorAll("." + selectorClass);
    }

    return allReplyLinks;
  }

  /**
   * On init function
   *
   * @since 1.0.0
   */
  function onInitFunction() {
    // grab the cookies name
    const cookiesAuthorAttr = getDataAttribute(cookiesConsentElement, "author");
    const cookiesAuthorEmailAttr = getDataAttribute(
      cookiesConsentElement,
      "authoremail"
    );

    if (!cookiesAuthorAttr || !cookiesAuthorEmailAttr) {
      console.error("Unknown author or author email");
      return;
    }

    function getCookie(cname) {
      let name = cname + "=";
      let decodedCookie = decodeURIComponent(document.cookie);
      let ca = decodedCookie.split(";");
      for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == " ") {
          c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
          return c.substring(name.length, c.length);
        }
      }
      return "";
    }

    // check cookies is set
    const cookiesAuthor = getCookie(cookiesAuthorAttr);
    const cookiesAuthorEmail = getCookie(cookiesAuthorEmailAttr);

    // check cookies is set
    if (!cookiesAuthor || !cookiesAuthorEmail) {
      return;
    }

    // if cookies is set, hide comment-form-cookies-consent field
    cookiesConsentElement.style.display = "none";
    cookiesConsentElement.nextElementSibling.innerHTML =
      "You are commenting as " + "<strong>" + cookiesAuthor + "</strong>";

    // if cookies is set set, hide comment-form-author-fields
    const commentFormAuthorFields = getElementById(
      config.commentFormAuthorFieldsId
    );
    commentFormAuthorFields.style.display = "none";

    // fill the author and email fields
    const authorField = getElementById(config.commentFormAuthorFieldId);
    const emailField = getElementById(config.commentFormEmailFieldId);
    authorField.value = cookiesAuthor;
    emailField.value = cookiesAuthorEmail;

    // remove required attribute from author and email fields
    authorField.removeAttribute("required");
    emailField.removeAttribute("required");
  }

  /**
   * Cancel event handler.
   *
   * @since 1.0.0
   *
   * @param {Event} event The calling event.
   */
  function cancelEvent(event) {
    event.preventDefault();
    let temporaryFormId = config.temporaryFormId;
    let temporaryElement = getElementById(temporaryFormId);

    // Clear the comment field.
    commentFieldElement.value = "";

    if (!temporaryElement || !respondElement) {
      // Conditions for cancel link fail.
      return;
    }

    getElementById(config.parentIdFieldId).value = "0";

    // Move the respond form back in place of the temporary element.
    temporaryElement.parentNode.replaceChild(respondElement, temporaryElement);
  }

  /**
   * Click event handler.
   *
   * @since 1.0.0
   *
   * @param {Event} event The calling event.
   */
  function clickEvent(event) {
    let replyLink = this,
      belowElementId = getDataAttribute(replyLink, "belowelement"),
      parentId = getDataAttribute(replyLink, "parentid"),
      respondId = getDataAttribute(replyLink, "respondelement"),
      postId = getDataAttribute(replyLink, "postid"),
      replyTo = getDataAttribute(replyLink, "replyto"),
      follow;

    if (!belowElementId || !parentId || !respondId || !postId) {
      /*
       * Theme or plugin defines own link via custom `wp_list_comments()` callback
       * and calls `moveForm()` either directly or via a custom event hook.
       */
      return;
    }

    /*
     * Third party comments systems can hook into this function via the global scope,
     * therefore the click event needs to reference the global scope.
     */
    follow = window.addComment.moveForm(
      belowElementId,
      parentId,
      respondId,
      postId,
      replyTo
    );
    if (false === follow) {
      event.preventDefault();
    }
  }

  /**
   * Backward compatible getter of data-* attribute.
   *
   * Uses element.dataset if it exists, otherwise uses getAttribute.
   *
   * @since 1.0.0
   *
   * @param {HTMLElement} Element DOM element with the attribute.
   * @param {string}      Attribute the attribute to get.
   *
   * @return {string}
   */
  function getDataAttribute(element, attribute) {
    if (supportsDataset) {
      return element.dataset[attribute];
    } else {
      return element.getAttribute("data-" + attribute);
    }
  }

  /**
   * Get element by ID.
   *
   * Local alias for document.getElementById.
   *
   * @since 1.0.0
   *
   * @param {HTMLElement} The requested element.
   */
  function getElementById(elementId) {
    return document.getElementById(elementId);
  }

  /**
   * Moves the reply form from its current position to the reply location.
   *
   * @since 2.7.0
   *
   * @memberOf addComment
   *
   * @param {string} addBelowId HTML ID of element the form follows.
   * @param {string} commentId  Database ID of comment being replied to.
   * @param {string} respondId  HTML ID of 'respond' element.
   * @param {string} postId     Database ID of the post.
   * @param {string} replyTo    Form heading content.
   */
  function moveForm(addBelowId, parentId, respondId, postId, replyTo) {
    // Get elements based on their IDs.
    let addBelowElement = getElementById(addBelowId);
    respondElement = getElementById(respondId);

    // Get the hidden fields.
    let parentIdField = getElementById(config.parentIdFieldId);
    let postIdField = getElementById(config.postIdFieldId);
    let element, cssHidden, style;

    if (!addBelowElement || !respondElement || !parentIdField) {
      // Missing key elements, fail.
      return;
    }

    addPlaceHolder(respondElement);

    // Set the value of the post.
    if (postId && postIdField) {
      postIdField.value = postId;
    }

    parentIdField.value = parentId;
    commentFieldElement.value = `@${replyTo} `;

    addBelowElement.parentNode.insertBefore(
      respondElement,
      addBelowElement.nextSibling
    );

    /*
     * This is for backward compatibility with third party commenting systems
     * hooking into the event using older techniques.
     */
    cancelElement.onclick = function () {
      return false;
    };

    // Focus on the first field in the comment form.
    try {
      for (let i = 0; i < commentFormElement.elements.length; i++) {
        element = commentFormElement.elements[i];
        cssHidden = false;

        // Get elements computed style.
        if ("getComputedStyle" in window) {
          // Modern browsers.
          style = window.getComputedStyle(element);
        } else if (document.documentElement.currentStyle) {
          // IE 8.
          style = element.currentStyle;
        }

        /*
         * For display none, do the same thing jQuery does. For visibility,
         * check the element computed style since browsers are already doing
         * the job for us. In fact, the visibility computed style is the actual
         * computed value and already takes into account the element ancestors.
         */
        if (
          (element.offsetWidth <= 0 && element.offsetHeight <= 0) ||
          style.visibility === "hidden"
        ) {
          cssHidden = true;
        }

        // Skip form elements that are hidden or disabled.
        if ("hidden" === element.type || element.disabled || cssHidden) {
          continue;
        }

        element.focus();
        // Stop after the first focusable element.
        break;
      }
    } catch (e) {}

    /*
     * false is returned for backward compatibility with third party commenting systems
     * hooking into this function.
     */
    return false;
  }

  /**
   * Add placeholder element.
   *
   * Places a place holder element above the #respond element for
   * the form to be returned to if needs be.
   *
   * @since 2.7.0
   *
   * @param {HTMLelement} respondElement the #respond element holding comment form.
   */
  function addPlaceHolder(respondElement) {
    let temporaryFormId = config.temporaryFormId;
    let temporaryElement = getElementById(temporaryFormId);
    let initialHeadingText = "";

    if (temporaryElement) {
      // The element already exists, no need to recreate.
      return;
    }

    temporaryElement = document.createElement("div");
    temporaryElement.id = temporaryFormId;
    temporaryElement.style.display = "none";
    temporaryElement.textContent = initialHeadingText;
    respondElement.parentNode.insertBefore(temporaryElement, respondElement);
  }

  return {
    init: init,
    moveForm: moveForm,
  };
})(window);
