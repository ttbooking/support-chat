export interface WinBoxProps {
    icon?: string;
    class?: string | string[];
    minheight?: string | number;
    minwidth?: string | number;
    overflow?: boolean;
}

export interface WinBoxModel {
    id: string;
    title: string;
    index: number;
    x: number;
    y: number;
    width: number;
    height: number;
    min: boolean;
    max: boolean;
    full: boolean;
    hidden: boolean;
    focused: boolean;
}
