#Proxy

Proxy is an EE2.0 Module used to substitue template tags with placeholder data, in order to more efficiently and effectively test your templates.

Before Proxy, I was creating a plethora of test entires to test various types of content that could be used in a Channel Field.  The process of creating all these dummy entries was time consuming, confusing, and led to me not testing as rigarously as I should have been.  Proxy was created to speed up the testing of templates, by allowing the developer to quicky swap in placeholder tag data when the template renders.

Follow me <a href='http://www.twitter.com/bryant_'>here</a> for more release updates.

##Requirements

* EE 2.0
* jQuery

##Installation
1. Add-ons -> Modules -> Proxy -> Install
2. Install both the Module and the Extension
3. Turn on the module by toggling the Substitution Enabled setting.

##Usage
* After turning on the Module by togging the Substituion Enabled setting, click on the Field Settings button.
* On the field settings field, select the Channel and Channel Field you want to setup a placeholder for, then click the + button.
* After the new field table has been created, set the preferences for that field:

- Substitution Type: Lets you decide when to substitue data. (Please see Known Issues for a few nuances)
- Substitution Method: How to substitue data; Either randomly, based on the placeholders you provide, or using the Placeholder Index, where you provide the index number of the placeholder which you want to use.
- Placeholder Type: Either Single or Loop.  Single is for normal EE tags.  Loop is for Matrix fields which loop over tags.
- Add Data(s): For single tags, add your placeholders here.  For loops, the form adds Number of Loops field, Tag Name field and Placeholder field. The Number of Loops field is how many times you want to loop to iterate.
- Placeholders: Where you actual placeholders will appear.

### Add Single Tag Placeholder:

After a field has been created and Placeholder Type is set to single, add placeholder text to the Placeholder field and click Add Placeholder.

After all fields and placeholders have been added, click Update to save your fields.

###Loop Example:

After a field has been created and Placeholder Type is set to loop, for a loop field that has 2 tags: {tag_1} and {tag_2}, with corresponding placeholders "test 1" and "test 2": 

The Tag names field should have:

	tag_1, tag_2

The Placeholders field should have:

	test 1 || test 2

Click Add Placeholder.

##Template Tags

There are no module specific template tags at this time.

## Known Issues

Proxy only works inside the Channel Entries Loop, and has only been tested with the standard text, textarea, dropdown, etc. fields; also the matrix field type.  Proxy may work with others, but it has not been tested thouroughly with them.

