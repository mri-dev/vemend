<div class="wrapper">
  <div class="content-holder">
    <div class="sel-dates">
      <div class="start">
        <input type="text" ng-disabled="!customDateEnable" ng-model="calendarModel.dateStart" formatted-date>
      </div>
      <div class="div">
        &mdash;
      </div>
      <div class="end">
        <input type="text" ng-disabled="!customDateEnable" ng-model="calendarModel.dateEnd" formatted-date>
      </div>
    </div>
    <?php if (true): ?>
      <md-date-range-picker
        first-day-of-week="1"
        one-panel="true"
        localization-map="localizationMap"
        selected-template="calendarModel.selectedTemplate"
        selected-template-name="calendarModel.selectedTemplateName"
        __custom-templates="customPickerTemplates"
        disable-templates="TD YD TW LW TM LM LY TY"
        date-start="calendarModel.dateStart"
        is-disabled-date="isDisabledDate($date)"
        date-end="calendarModel.dateEnd">
      </md-date-range-picker>
    <?php else: ?>
      <md-calendar ng-model="birthday"></md-calendar>
    <?php endif; ?>

  </div>
</div>
