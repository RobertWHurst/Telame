Telame Database Structure
==========================

***[] = length   () = index type***

****
 - **Profiles**
  - id (primary)
  - slug [?]
  - profile_meta_id (index)
  - password [40]
  - email [120]
  - joined
  - accessed
  - type
****

 - **Profile Meta**
  - id (primary)
  - profile_id (index)
  - key (index)
  - value
****
 - **Attachments**
  - id (primary)
  - attachment_meta_id (index)
  - type [20]
  - name [120]
  - uri [240]
  - profile_id (index)

***this document is incomplete***  
more information will be added as tables are defined, deleted, or modified.