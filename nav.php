<?php

foreach ($items as $item) {

    $attrs = ['class' => []];
    $children = isset($item->children);
    $indention = str_pad("\n", $level + 1, "\t");
    $title = $item->title;

    // Active?
    if ($item->active) {
        $attrs['class'][] = 'uk-active uk-open';
    }

    // Icon
    $icon = $item->config->get('icon', '');
    if (preg_match('/\.(gif|png|jpg|svg)$/i', $icon)) {
        $icon = "<img src=\"{$icon}\" alt=\"{$item->title}\">";
    } elseif ($icon) {
        $icon = "<span class=\"uk-margin-small-right\" uk-icon=\"icon: {$icon}\"></span>";
    }

    // Show Icon only
    if ($icon && $item->config->get('icon-only')) {
        $title = '';
    }

    // Header
    if ($item->type == 'header' || ($item->type === 'custom' && $item->url === '#')) {

        $title = $icon.$title;

        // Divider
        if ($item->divider && !$children) {
            $title = '';
            $attrs['class'][] = 'uk-nav-divider';
        } elseif ($params->get('accordion') && $children) {
            $title = "<a href=\"#\">{$title}</a>";
        } else {
            $attrs['class'][] = 'uk-nav-header';
        }

        // Link
    } else {

        $link = [];
        if ($children) {
            if (isset($item->url)) {
                $link['href'] = "";
            }
        } else {
            if (isset($item->url)) {
                $link['href'] = $item->url;
            }
        }

        if (isset($item->target)) {
            $link['target'] = $item->target;
        }

        if (isset($item->anchor_title)) {
            $link['title'] = $item->anchor_title;
        }

        // Additional Class
        if (isset($item->class)) {
            $link['class'] = $item->class;
        }

        if ($children) {
            $js = "onclick='location.href = \"$item->url\"'";
            $title = "<a{$this->attrs($link)}><span $js>{$icon}{$title}</span></a>";
        } else {
            $title = "<a{$this->attrs($link)}>{$icon}{$title}</a>";
        }



    }

    // Children?
    if ($children) {

        $attrs['class'][] = 'uk-parent';

        $children = ['class' => []];

        if ($level == 1) {
            $children['class'][] = 'uk-nav-sub uk-nav-parent-icon uk-nav-accordion';
        }

        if ($level == 2) {
            $children['class'][] = 'uk-nav-sub uk-nav-parent-icon uk-nav-accordion';
        }

        $children = "{$indention}<ul{$this->attrs($children)} uk-nav>\n" . $this->self(['items' => $item->children, 'level' => $level + 1]) . "</ul>";
    }

    echo "{$indention}<li{$this->attrs($attrs)}>{$title}{$children}</li>";
}
