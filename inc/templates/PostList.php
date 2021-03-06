<?php
namespace Vincit\Template;

/**
 * Component that lists posts from the current query context, or uses the provided query.
 *
 * @param array $data
 */
function PostList($data = []) {
  $data = params([
    "query" => null, // Pass in a new WP_Query() to see magic
    "template" => __NAMESPACE__ . "\PostListItem", // could also pass a callable directly
  ], $data);

  // And now, for my next trick...
  // The main query uses global functions, but custom queries resort to
  // object methods. That requires two loops, or this.
  if (is_null($data["query"])) {
    $havePosts = "have_posts";
    $thePost = "the_post";
  } else {
    // https://stackoverflow.com/questions/16380745/is-is-possible-to-store-a-reference-to-an-object-method
    $havePosts = [$data["query"], "have_posts"];
    $thePost = [$data["query"], "the_post"];
  } ?>

  <div class="post-list"><?php
  while ($havePosts()) { $thePost();
    $data["template"]([
      "title" => get_the_title(),
      "image" => get_post_thumbnail_id(),
      "content" => \Vincit\Post\excerpt(),
      "permalink" => get_permalink(),
    ]);
  } wp_reset_postdata(); ?>
  </div><?php
}
