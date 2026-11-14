// skeleton.js - Control de visualización de skeletons / loaders
export const showSkeleton = (skeletonSelector, hideSelector = null) => {
    if (hideSelector) $(hideSelector).hide();
    $(skeletonSelector).fadeIn(200);
};

export const hideSkeleton = (skeletonSelector, showSelector = null) => {
    $(skeletonSelector).fadeOut(200, () => {
        if (showSelector) $(showSelector).fadeIn(200);
    });
};
