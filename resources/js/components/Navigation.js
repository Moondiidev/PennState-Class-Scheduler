import React from 'react';
import ReactDOM from 'react-dom';

class Navigation extends React.Component {

    constructor(props){
        super(props);
        this.nav = {
            'links' : JSON.parse(this.props.nav)
        }
       // console.log('data from component', this.nav.links);
    };


    render(){

        console.log(this.nav.links);

        const navLinks = this.nav.links.map( (link, x) => {
            return (
                <li className="mr-6" key={x}>
                    <a className={` ${link.active ? 'text-gray-500 cursor-text' : 'text-blue-500 hover:text-blue-800'}`} href={`${link.uri}`}>{link.name}</a>
                </li>
            )
        });

        return (
            <ul className="flex p-6">
                {navLinks}
            </ul>
        );
    }
}

export default Navigation;

if (document.getElementById('navigation')) {

    // get props
    const data = document.getElementById('navigation').getAttribute('data');

    ReactDOM.render(<Navigation nav={data}/>, document.getElementById('navigation'));
}
