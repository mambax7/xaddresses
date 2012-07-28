#
# Table structure for table `xaddresses_locationcategory`
#

CREATE TABLE `xaddresses_locationcategory` (
  `cat_id`              int(12) unsigned        NOT NULL auto_increment,
  `cat_pid`             int(12) unsigned        NOT NULL default '0',
  `cat_title`           varchar(255)            NOT NULL default '',
  `cat_imgurl`          varchar(255)            NOT NULL default '',
  `cat_description`     text                    NOT NULL,
  `cat_dohtml`          tinyint(2)              NOT NULL default '0',
  `cat_weight`          int(11)                 NOT NULL default '0',
  `cat_map_type`        varchar(255)            NOT NULL default '',
  PRIMARY KEY  (`cat_id`),
  KEY `cat_pid` (`cat_pid`)
) ENGINE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `xaddresses_location`
#

CREATE TABLE `xaddresses_location` (
  `loc_id`              int(12) unsigned        NOT NULL auto_increment,
  `loc_cat_id`          int(12) unsigned        NOT NULL default '0',
  `loc_title`           varchar(255)            NOT NULL default '',
  `loc_lat`             varchar(255)            NOT NULL default '',
  `loc_lng`             varchar(255)            NOT NULL default '',
  `loc_elevation`       varchar(255)            NOT NULL default '',
  `loc_zoom`            varchar(255)            NOT NULL default '',
  `loc_submitter`       int(11)                 NOT NULL default '0',
  `loc_date`            int(12)                 NOT NULL default '0',
  `loc_status`          tinyint(2)              NOT NULL default '0',
  `loc_suggested`       tinyint(2)              NOT NULL default '0',
  `loc_comments`        int(11) unsigned        NOT NULL default '0',
  `loc_rating`          double(6,4)             NOT NULL default '0.0000',
  `loc_votes`           int(11) unsigned        NOT NULL default '0',
  PRIMARY KEY  (`loc_id`),
  KEY `loc_cat_id` (`loc_cat_id`),
  KEY `loc_status` (`loc_status`),
  KEY `loc_suggested` (`loc_suggested`),
  KEY `loc_title` (`loc_title`)
) ENGINE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `xaddresses_field`
#

CREATE TABLE `xaddresses_field` (
  `field_id`            int(12) unsigned        NOT NULL auto_increment,
  `cat_id`              smallint(5) unsigned    NOT NULL default '0',
  `field_type`          varchar(30)             NOT NULL default '',
  `field_valuetype`     tinyint(2) unsigned     NOT NULL default '0',
  `field_name`          varchar(255)            NOT NULL default '',
  `field_title`         varchar(255)            NOT NULL default '',
  `field_description`   text,
  `field_required`      tinyint(1) unsigned     NOT NULL default '0',
  `field_length`        smallint(6) unsigned    NOT NULL default '0',
  `field_maxlength`     smallint(6) unsigned    NOT NULL default '0',
  `field_weight`        smallint(6) unsigned    NOT NULL default '0',
  `field_default`       text,
  `field_notnull`       tinyint(1) unsigned     NOT NULL default '0',
  `field_edit`          tinyint(1) unsigned     NOT NULL default '0',
  `field_show`          tinyint(1) unsigned     NOT NULL default '0',
  `field_config`        tinyint(1) unsigned     NOT NULL default '0',
  `field_options`       text,
  `field_extras`        text,
  PRIMARY KEY  (`field_id`),
  UNIQUE KEY `field_name` (`field_name`)
) ENGINE=MyISAM;

#
# Table structure for table `xaddresses_fieldcategory`
#

CREATE TABLE `xaddresses_fieldcategory` (
  `cat_id`          smallint(5) unsigned    NOT NULL auto_increment,
  `cat_title`       varchar(255)            NOT NULL default '',
  `cat_description` text,
  `cat_weight`      smallint(5) unsigned    NOT NULL default '0',
  
  PRIMARY KEY  (`cat_id`)
) ENGINE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `xaddresses_broken`
#

CREATE TABLE `xaddresses_broken` (
  `report_id`           int(12) unsigned        NOT NULL auto_increment,
  `loc_id`              int(12) unsigned        NOT NULL default '0',
  `report_description`  text,
  `report_sender`       int(11)                 NOT NULL default '0',
  `report_ip`           varchar(20)             NOT NULL default '',
  `report_date`         int(12)                 NOT NULL default '0',

  PRIMARY KEY  (`report_id`),
  KEY `loc_id` (`loc_id`),
  KEY `report_sender` (`report_sender`),
  KEY `report_ip` (`report_ip`)
) ENGINE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `xaddresses_modify`
#

CREATE TABLE `xaddresses_modify` (
  `suggest_id`          int(12) unsigned        NOT NULL auto_increment,
  `loc_id`              int(12) unsigned        NOT NULL default '0',
  `suggest_loc_id`      int(12) unsigned        NOT NULL default '0',
  `suggest_description` text,
  `suggest_sender`      int(11)                 NOT NULL default '0',
  `suggest_ip`          varchar(20)             NOT NULL default '',
  `suggest_date`        int(12)                 NOT NULL default '0',

  PRIMARY KEY  (`suggest_id`),
  KEY `loc_id` (`loc_id`),
  KEY `suggest_sender` (`suggest_sender`),
  KEY `suggest_ip` (`suggest_ip`)
) ENGINE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `xaddresses_votedata`
#

CREATE TABLE `xaddresses_votedata` (
  `rating_id`           int(12) unsigned        NOT NULL auto_increment,
  `loc_id`              int(12) unsigned        NOT NULL default '0',
  `rating_user`         int(11)                 NOT NULL default '0',
  `rating`              tinyint(3) unsigned     NOT NULL default '0',
  `rating_hostname`     varchar(60)             NOT NULL default '',
  `rating_timestamp`    int(10)                 NOT NULL default '0',
  PRIMARY KEY  (`rating_id`),
  KEY `rating_user` (`rating_user`),
  KEY `rating_hostname` (`rating_hostname`),
  KEY `loc_id` (`loc_id`)
) ENGINE=MyISAM;
# --------------------------------------------------------







# ------------------- IN PROGRESS FROM HERE

#
# Table structure for table `xaddresses_marker`
#

CREATE TABLE `xaddresses_marker` (
  `marker_id`              int(12) unsigned        NOT NULL auto_increment,
  `marker_title`           varchar(255)            NOT NULL default '',
  `marker_description`     text                    NOT NULL,
  `marker_image`           varchar(255)            NOT NULL default '',
  `marker_shadow`          varchar(255)            NOT NULL default '',
  PRIMARY KEY  (`marker_id`)
) ENGINE=MyISAM;
# --------------------------------------------------------