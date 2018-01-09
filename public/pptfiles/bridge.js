function onRegistered(slidesCount, firstStepCount){
    window.client && window.client.onRegistered(slidesCount, firstStepCount);
}

function onStepChanged(slideIndex, stepIndex, trigger, isBack){
    window.client && window.client.onStepChanged(slideIndex, stepIndex, trigger, isBack)
}

function gotoNextStep() {
    execNext()
}

function gotoPreviousStep() {
    ExecGoBack()
}
function gotoStep(slideIndex, stepIndex, trigger, isBack) {
    syncExec(slideIndex, stepIndex, trigger, isBack)
}
$(document).ready(function () {
    window.onRegistered && window.onRegistered(window._control.length, window._control[0].animations && window._control[0].animations.length)
})
